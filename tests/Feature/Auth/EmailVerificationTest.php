<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * 이메일 인증 테스트
     *
     * @return void
     */
    public function testVerify()
    {
        $user = User::factory()->unverified()->create();

        $id = $user->id;
        $hash = sha1($user->email);

        $this->actingAs($user)
            ->withoutMiddleware(ValidateSignature::class)
            ->get("/email/verify/{$id}/{$hash}")
            ->assertRedirect();

        $this->assertTrue($user->hasVerifiedEmail());
    }

    /**
     * 이메일이 인증되지 않은 경우 테스트
     *
     * @return void
     */
    public function testNotice()
    {
        $this->withoutMiddleware(Authenticate::class)
            ->get('/email/verify')
            ->assertViewIs('auth.verify-email');
    }

    /**
     * 이메일 인증 테스트
     *
     * @return void
     */
    public function testSend()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertRedirect();

        Notification::assertSentTo(
            [$user], VerifyEmail::class);
    }
}
