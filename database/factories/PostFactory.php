<?php

use Faker\Generator as Faker;

$factory->define(\App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->unique->word(60),
        'body' => $faker->word,
    ];
});
