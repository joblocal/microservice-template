<?php

namespace Tests\App\Requests;

use Tests\TestCase;

class StatusTest extends TestCase
{
    public function test(): void
    {
        $this
            ->get('/status')
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/json')
            ->assertExactJson([
                'message' => 'alive',
            ]);
    }
}
