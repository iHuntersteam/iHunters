<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Helpers\UserHelpers;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username'       => $faker->userName,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt('password'),
        'remember_token' => str_random(10),
        'is_admin'       => random_int(0, 1),
        'my_admin'       => UserHelpers::randomAdminId(),
    ];
});

$factory->define(\App\Models\Person::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(\App\Models\Site::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->domainWord . "." . $faker->tld,
    ];
});

$factory->define(\App\Models\Page::class, function (Faker\Generator $faker) {
    $site = \App\Helpers\SiteHelpers::randomSite();

    return [
        'url'             => $site->name . "/" . str_random(8) . "." . $faker->tld,
        'site_id'         => $site->id,
        'found_date_time' => $faker->dateTime,
        'last_scan_date'  => $faker->dateTime,
    ];
});
