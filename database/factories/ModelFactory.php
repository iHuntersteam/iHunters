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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\Person::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(\App\Models\Site::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->domainName
    ];
});

$factory->define(\App\Models\Page::class, function (Faker\Generator $faker) {
    $site = \App\Helpers\SiteHelpers::randomSite();
    return [
        'url' => $site->name . "/" . $faker->domainWord . "." . $faker->tld,
        'site_id' => $site->id,
        'found_date_time' => \Carbon\Carbon::now(),
        'last_scan_date' => null
    ];
});
