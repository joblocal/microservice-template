<?php

/** @var Factory $factory */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factory;

$factory->define(App\Models\EventLog::class, function (\Faker\Generator $faker) {
    $eventAt = Carbon::now();
    $publicationId = $faker->numberBetween();
    $subject = $faker->randomElement(['created', 'deleted']);

    return [
        'payload' => json_encode([
            'event_at' => $eventAt,
            'publication_id' => $publicationId,
            'subject' => $subject,
        ]),
        'event_at' => $eventAt,
        'subject' => $subject,
        'publication_id' => $publicationId,
    ];
});
