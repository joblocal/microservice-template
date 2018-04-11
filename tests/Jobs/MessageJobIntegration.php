<?php

namespace Tests\Jobs;

use App\Jobs\MessageJob;
use App\Models\EventLog;
use App\Models\Publication;
use Tests\Traits\DatabaseMigrations;
use Tests\TestCase;

class MessageJobIntegration extends TestCase
{
    use DatabaseMigrations;

    private $id;
    private $subject;
    private $event_at;
    private $event_at_execute;
    private $event_at_not_execute;

    public function setUp()
    {
        parent::setUp();

        $interval = new \DateInterval('PT10S');

        $this->id = 5;
        $this->subject = 'created';
        $this->event_at = (new \DateTime())->format(\DateTime::ISO8601);
        $this->event_at_execute = (new \DateTime())->add($interval)->format(\DateTime::ISO8601);
        $this->event_at_not_execute = (new \DateTime())->sub($interval)->format(\DateTime::ISO8601);

        $this->mockRemoteApi();
    }

    public function testConstructor()
    {
        $job = new MessageJob($this->id, $this->subject, $this->event_at);

        $this->assertEquals($this->id, $job->id);
        $this->assertEquals($this->subject, $job->subject);
        $this->assertEquals($this->event_at, $job->event_at);
    }

    public function testHandleReturnValue()
    {
        $job = new MessageJob($this->id, $this->subject, $this->event_at);
        $this->assertTrue($job->handle());

        $job = new MessageJob(null, $this->subject, $this->event_at);
        $this->assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->event_at_not_execute);
        $this->assertFalse($job->handle());

        $job = new MessageJob($this->id, $this->subject, $this->event_at_execute);
        $this->assertTrue($job->handle());
    }

    public function testHandle()
    {
        $job = new MessageJob($this->id, $this->subject, $this->event_at);
        $job->handle();

        $event = EventLog::query()->first();

        $this->assertNotNull($event);
        $this->assertEquals($this->id, $event->publication_id);
        $this->assertEquals($this->event_at, $event->event_at);
        $this->assertEquals($this->subject, json_decode($event->payload)->subject);
    }
}
