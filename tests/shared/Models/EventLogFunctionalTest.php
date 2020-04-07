<?php

namespace Tests\Shared\Models;

use App\Models\EventLog;
use Carbon\Carbon;
use DateInterval;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventLogFunctionalTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecuteEvent()
    {
        $interval = new DateInterval('PT10S');

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create();
        $this->assertTrue($eventLog->shouldExecute());

        $eventAtExecute = Carbon::parse($eventLog->event_at)->add($interval);
        $eventAtNotExecute = Carbon::parse($eventLog->event_at)->sub($interval);
        $publicationId = $eventLog->publication_id;

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create([
            'publication_id' => $publicationId,
            'event_at' => $eventAtNotExecute,
        ]);
        $this->assertFalse($eventLog->shouldExecute());

        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create([
            'publication_id' => $publicationId,
            'event_at' => $eventAtExecute,
        ]);

        $this->assertTrue($eventLog->shouldExecute());
    }
}
