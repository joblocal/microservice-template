<?php

namespace Tests\Requests;

use Tests\TestCase;

class PingTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->get('/ping');
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
        $this->assertEquals(json_encode(['message' => 'pong']), $this->response->content());
    }
}
