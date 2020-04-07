<?php

namespace Tests\Worker\Jobs;

use App\Jobs\MessageJob;
use App\Models\EventLog;
use DateInterval;
use DateTime;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class MessageJobIntegration extends TestCase
{
    use DatabaseTransactions;

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

        $this->assertEquals($this->id, $job->id);
        $this->assertEquals($this->subject, $job->subject);
        $this->assertEquals($this->eventAt, $job->eventAt);
    }

    public function testHandleReturnValue()
    {
        $job = new MessageJob($this->id, $this->subject, $this->eventAt);
        $this->assertTrue($job->handle());

        $job = new MessageJob(null, $this->subject, $this->eventAt);
        $this->assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->eventAtNotExecute);
        $this->assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->eventAtExecute);
        $this->assertTrue($job->handle());
    }

    public function testHandle()
    {
        $job = new MessageJob($this->id, $this->subject, $this->eventAt);
        $job->handle();

        $event = EventLog::query()->first();

        $this->assertNotNull($event);
        $this->assertEquals($this->id, $event->publication_id);
        $this->assertEquals($this->eventAt, $event->event_at);
        $this->assertEquals($this->subject, json_decode($event->payload)->subject);
    }
}
