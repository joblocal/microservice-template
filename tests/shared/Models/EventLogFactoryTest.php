<?php

namespace Tests\Shared\Models;

use App\Models\EventLog;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventLogFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test for the event log table.
     *
     * @return void
     */
    public function testFactory()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->create();

        $this->seeInDatabase('event_logs', [
            'publication_id' => $eventLog->publication_id,
        ]);
    }
}
