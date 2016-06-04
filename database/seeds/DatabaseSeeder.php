<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->personsSeeder();
        $this->sitesSeeder();
        $this->pageSeeder();
        $this->rankTableSeeder(3000);
        $this->userSeeder();
    }

    private function personsSeeder()
    {
        factory(\App\Models\Person::class, 10)->create();
    }

    private function sitesSeeder()
    {
        factory(\App\Models\Site::class, 20)->create();
    }

    private function pageSeeder()
    {
        factory(\App\Models\Page::class, 100)->create();
    }

    private function rankTableSeeder($rowCount)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $rowCount; $i++) {
            DB::table('person_page_rank')->insert([
                'person_id' => \App\Helpers\PersonHelpers::randomPersonId(),
                'page_id'   => \App\Helpers\PageHelpers::randomPageId(),
                'rank'      => $faker->randomNumber(),
            ]);
        }
    }

    private function userSeeder()
    {
        //admins

        factory(User::class, 5)->create([
            'is_admin' => 1,
            'my_admin' => null,
        ]);

        //users

        factory(User::class, 17)->create([
            'is_admin' => 0,
        ]);
    }
}
