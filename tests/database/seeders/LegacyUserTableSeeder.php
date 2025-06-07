<?php

namespace Tests\Seeds;

use Illuminate\Database\Seeder;
use Tests\Models\Profile;
use Tests\Models\Tag;
use Tests\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->count(50)
            ->create()
            ->each(function ($u) {
                $u->profile()->save(Profile::factory()->make());
                $u->tags()->saveMany(Tag::factory()->count(5)->make());
                $u->data = ['json' => ['field' => random_int(0, 50)]];
            });
    }
}
