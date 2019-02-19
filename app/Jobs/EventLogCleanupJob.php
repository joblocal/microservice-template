<?php

namespace App\Jobs;

use DB;
use Log;

use Carbon\Carbon;

class EventLogCleanupJob extends Job
{
    public function handle()
    {
        $keepUntil = Carbon::now()->subDay();

        DB::table('event_logs')
            ->where('created_at', '<', $keepUntil->toDateTimeString())
            ->delete();

        Log::notice(sprintf(
            'Deleted event log entries before %s.',
            $keepUntil->toRfc2822String()
        ));
    }
}
