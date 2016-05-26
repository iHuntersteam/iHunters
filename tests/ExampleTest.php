<?php

use App\Models\Site;
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
        /** @var \App\Models\Person $person */
        $person = \App\Models\Person::first();

        dd($person->rank($person->pages[0]));
    }
}
