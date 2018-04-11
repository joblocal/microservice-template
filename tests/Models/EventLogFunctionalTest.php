<?php

namespace Tests\Models;

use App\Models\EventLog;
use Tests\Traits\DatabaseMigrations;
use Tests\TestCase;

class EventLogFunctionalTest extends TestCase
{
    use DatabaseMigrations;

    public function testExecuteEvent()
    {
        $interval = new \DateInterval('PT10S');

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create();
        $this->assertTrue($eventLog->shouldExecute());

        $event_at_execute = \DateTime::createFromFormat(\DateTime::ISO8601, $eventLog->event_at)
            ->add($interval);
        $event_at_not_execute = \DateTime::createFromFormat(\DateTime::ISO8601, $eventLog->event_at)
            ->sub($interval);
        $publication_id = $eventLog->publication_id;

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create([
            'publication_id' => $publication_id,
            'event_at' => $event_at_not_execute->format(\DateTime::ISO8601),
        ]);
        $this->assertFalse($eventLog->shouldExecute());

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create([
            'publication_id' => $publication_id,
            'event_at' => $event_at_execute->format(\DateTime::ISO8601),
        ]);
        $this->assertTrue($eventLog->shouldExecute());
    }
}
