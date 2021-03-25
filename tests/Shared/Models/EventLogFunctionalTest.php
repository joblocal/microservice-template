<?php

namespace Tests\Shared\Models;

use App\Models\EventLog;
use Carbon\Carbon;
use DateInterval;
use Tests\TestCase;

class EventLogFunctionalTest extends TestCase
{
    public function testExecuteEvent()
    {
        $interval = new DateInterval('PT10S');

        /** @var EventLog $eventLog */
        $eventLog = EventLog::factory()->create();
        self::assertTrue($eventLog->shouldExecute());

        $eventAtExecute = Carbon::parse($eventLog->event_at)->add($interval);
        $eventAtNotExecute = Carbon::parse($eventLog->event_at)->sub($interval);
        $publicationId = $eventLog->publication_id;

        /** @var EventLog $eventLog */
        $eventLog = EventLog::factory()->create([
            'publication_id' => $publicationId,
            'event_at' => $eventAtNotExecute,
        ]);

        self::assertFalse($eventLog->shouldExecute());

        /** @var EventLog $eventLog */
        $eventLog = EventLog::factory()->create([
            'publication_id' => $publicationId,
            'event_at' => $eventAtExecute,
        ]);

        self::assertTrue($eventLog->shouldExecute());
    }
}
