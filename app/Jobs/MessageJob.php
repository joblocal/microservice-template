<?php

namespace App\Jobs;

use App\Models\EventLog;

class MessageJob extends Job
{
    public $id;
    public $subject;
    public $event_at;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $subject, $event_at)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->event_at = $event_at;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventLog = EventLog::create([
            'payload' => json_encode([
                'id' => $this->id,
                'event_at' => $this->event_at,
                'subject' => $this->subject,
            ]),
            'publication_id' => $this->id,
            'subject' => $this->subject,
            'event_at' => $this->event_at,
        ]);

        if ($eventLog->wasRecentlyCreated) {
            if ($eventLog->shouldExecute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
