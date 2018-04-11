<?php

namespace App\Listeners;

use Aws\CloudWatch\CloudWatchClient;
use Illuminate\Queue\Events\JobProcessed;

class JobProcessedEventListener
{
    /** @var CloudWatchClient client */
    protected $client;

    /**
     * JobProcessedEventListener constructor.
     *
     * @param CloudWatchClient $client
     */
    public function __construct(CloudWatchClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send metrics data to cloudwatch.
     *
     * @param JobProcessed $event
     * @return void
     */
    public function handle(JobProcessed $event)
    {
        $this->client->putMetricData([
            'MetricData' => [
                [
                    'MetricName' => 'JobProcessed',
                    'Timestamp' => new \DateTime('now'),
                    'Value' => 1,
                ],
            ],
            'Namespace' => 'ms-template-' . app()->environment(),
        ]);
    }
}
