<?php

use App\Models\Site;
use App\User;
use Faker\Generator;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $user = User::where('email', 'bakautovalex@gmail.com')->first();

        $newPassword = str_random(7);

        dd($user->update([
            'password' => bcrypt($newPassword),
        ]));
    }
}
