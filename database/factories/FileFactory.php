<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'name' => $faker->name,
        'path' => $faker->url,
        'type' => 'image'
    ];
});
