<?php

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
        $this->pageSeeder();
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


}
