<?php

namespace Tests\Worker\Jobs;

use App\Jobs\MessageJob;
use App\Models\EventLog;
use DateInterval;
use DateTime;
use Tests\TestCase;

class MessageJobIntegration extends TestCase
{
    private $id;
    private $subject;
    private $eventAt;
    private $eventAtExecute;
    private $eventAtNotExecute;

    public function setUp(): void
    {
        parent::setUp();

        $interval = new DateInterval('PT10S');

        $this->id = 5;
        $this->subject = 'created';
        $this->eventAt = (new DateTime())->format(DateTime::ISO8601);
        $this->eventAtExecute = (new DateTime())->add($interval)->format(DateTime::ISO8601);
        $this->eventAtNotExecute = (new DateTime())->sub($interval)->format(DateTime::ISO8601);

        $this->mockRemoteApi();
    }

    public function testConstructor()
    {
        $job = new MessageJob($this->id, $this->subject, $this->eventAt);

        self::assertEquals($this->id, $job->id);
        self::assertEquals($this->subject, $job->subject);
        self::assertEquals($this->eventAt, $job->eventAt);
    }

    public function testHandleReturnValue()
    {
        $job = new MessageJob($this->id, $this->subject, $this->eventAt);
        self::assertTrue($job->handle());

        $job = new MessageJob(null, $this->subject, $this->eventAt);
        self::assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->eventAtNotExecute);
        self::assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->eventAtExecute);
        self::assertTrue($job->handle());
    }

    public function testHandle()
    {
        $job = new MessageJob($this->id, $this->subject, $this->eventAt);
        $job->handle();

        $event = EventLog::query()->first();

        self::assertNotNull($event);
        self::assertEquals($this->id, $event->publication_id);
        self::assertEquals($this->eventAt, $event->event_at);
        self::assertEquals($this->subject, json_decode($event->payload)->subject);
    }
}
