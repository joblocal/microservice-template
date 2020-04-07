<?php

namespace App\Jobs;

use App\Models\EventLog;

class MessageJob extends Job
{
    public $id;
    public $subject;
    public $eventAt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $subject, $eventAt)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->eventAt = $eventAt;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        $eventLog = EventLog::create([
            'payload' => json_encode([
                'id' => $this->id,
                'event_at' => $this->eventAt,
                'subject' => $this->subject,
            ]),
            'publication_id' => $this->id,
            'subject' => $this->subject,
            'event_at' => $this->eventAt,
        ]);

        return $eventLog->wasRecentlyCreated && $eventLog->shouldExecute();
    }
}
