<?php

namespace Tests\App\Requests;

use Tests\TestCase;

class StatusTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->get('/status');
    }

    public function testStatusCode()
    {
        $this->assertResponseOk();
    }

    public function testContentType()
    {
        $this->seeHeader('Content-Type', 'application/json');
    }

    public function testResponseBody()
    {
        $this->assertEquals(json_encode(['message' => 'alive']), $this->response->content());
    }
}
