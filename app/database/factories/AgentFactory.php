<?php

use Faker\Generator as Faker;

$factory->define(\App\Agent::class, function (Faker $faker) {
    return [
        'business_name' => $faker->company,
        'name' => $faker->name,
        'email' => $faker->regexify('[a-z0-9]{5}') . "@carsplanner.com",
        'phone' => $faker->phoneNumber,
        'password' => $faker->password,
        'notes' => $faker->regexify('[A-Za-z0-9]{30}')
    ];
});
