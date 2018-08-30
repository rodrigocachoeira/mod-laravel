<?php

use Faker\Generator as Faker;

$factory->define(\App\Business\Entities\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->unique->word(60),
        'body' => $faker->text,
    ];
});
