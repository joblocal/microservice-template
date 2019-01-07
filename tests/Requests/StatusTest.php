<?php

namespace Tests\Requests;

use Tests\TestCase;

class StatusTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->get('/v4/status');
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
