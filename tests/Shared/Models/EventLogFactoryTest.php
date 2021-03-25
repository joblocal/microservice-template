<?php

namespace Tests\Shared\Models;

use App\Models\EventLog;
use Tests\TestCase;

class EventLogFactoryTest extends TestCase
{
    /**
     * A test for the event log table.
     *
     * @return void
     */
    public function testFactory()
    {
        /** @var EventLog $eventLog */
        $eventLog = EventLog::factory()->create();

        $this->assertDatabaseHas('event_logs', [
            'publication_id' => $eventLog->publication_id,
        ]);
    }
}
