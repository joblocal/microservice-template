<?php

namespace Tests\Jobs;

use App\Jobs\EventLogCleanupJob;
use App\Models\EventLog;
use Tests\Traits\DatabaseMigrations;
use Tests\TestCase;
use Carbon\Carbon;
use DB;
use Log;

class EventLogCleanupJobTest extends TestCase
{
    use DatabaseMigrations;

    public function testHandle()
    {
        $newCount = rand(1, 10);
        $oldEventLogs = factory(EventLog::class, 100)->create([
            'created_at' => Carbon::now()->subDay()->subSecond(),
        ]);
        $newEventLogs = factory(EventLog::class, $newCount)->create();

        Log::shouldReceive('notice');

        $job = new EventLogCleanupJob();
        $job->handle();

        $this->assertEquals($newCount, DB::table('event_logs')->count());
    }
}
