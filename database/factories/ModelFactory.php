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

/**
 * User factory
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

/**
 * EventLog factory
 */
$factory->define(App\Models\EventLog::class, function (Faker\Generator $faker) {
    $event_at = $faker->iso8601();
    $publication_id = $faker->numberBetween();
    $subject = $faker->randomElement(['created', 'deleted']);

    return [
        'payload' => json_encode([
            'event_at' => $event_at,
            'publication_id' => $publication_id,
            'subject' => $subject,
        ]),
        'event_at' => $event_at,
        'subject' => $subject,
        'publication_id' => $publication_id,
    ];
});
