<?php

namespace Database\Factories;

use App\Models\EventLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

class EventLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws JsonException
     */
    public function definition(): array
    {
        $eventAt = now();
        $publicationId = $this->faker->numberBetween();
        $subject = $this->faker->randomElement(['created', 'deleted']);

        return [
            'payload' => json_encode([
                'event_at' => $eventAt,
                'publication_id' => $publicationId,
                'subject' => $subject,
            ], JSON_THROW_ON_ERROR),
            'event_at' => $eventAt,
            'subject' => $subject,
            'publication_id' => $publicationId,
        ];
    }
}
