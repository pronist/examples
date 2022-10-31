<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::all()->each(function (Post $post) {
            $factory = Comment::factory()
                ->for($post, 'commentable')
                ->state(function (array $attributes) {
                    return [
                        'user_id' => User::pluck('id')->random(),
                    ];
                });

            $factory->has($factory->count(2), 'replies')->create();
            $factory->create();
        });
    }
}
