<?php

namespace Tests\Models;

use \App\Models\EventLog;
use Tests\TestCase;

class EventLogValidationTest extends TestCase
{
    public function testPayloadOnBlank()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'payload' => null,
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testPayloadOnJson()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'payload' => 'not valid json',
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testPublicationIDOnBlank()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'publication_id' => null,
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testEventAtOnBlank()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'event_at' => null,
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testEventAtOnDate()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'event_at' => new \DateTime(),
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testSubjectOnBlank()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'subject' => null,
        ]);

        $this->assertFalse($eventLog->isValid());
    }

    public function testSubjectOnType()
    {
        /** @var EventLog $eventLog */
        $eventLog = factory(EventLog::class)->make([
            'subject' => 'test',
        ]);

        $this->assertFalse($eventLog->isValid());
    }
}
