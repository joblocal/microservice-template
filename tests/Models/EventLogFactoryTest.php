<?php

namespace Tests\Models;

use App\Models\EventLog;
use Tests\Traits\DatabaseMigrations;
use Tests\TestCase;

class EventLogFactoryTest extends TestCase
{
    use DatabaseMigrations;

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

    public function testFactoryIsValid()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make();

        $this->assertTrue($eventLog->isValid());
    }
}
