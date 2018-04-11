<?php

namespace App\Listeners;

use Aws\CloudWatch\CloudWatchClient;
use Illuminate\Queue\Events\JobFailed;

class JobFailedEventListener
{
    /** @var CloudWatchClient client */
    protected $client;

    /**
     * JobFailedEventListener constructor.
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
     * @param JobFailed $event
     * @return void
     */
    public function handle(JobFailed $event)
    {
        $this->client->putMetricData([
            'MetricData' => [
                [
                    'MetricName' => 'JobFailed',
                    'Timestamp' => new \DateTime('now'),
                    'Value' => 1,
                ],
            ],
            'Namespace' => 'ms-template-' . app()->environment(),
        ]);
    }
}
