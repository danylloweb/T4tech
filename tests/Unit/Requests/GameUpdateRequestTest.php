<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\GameUpdateRequest;

class GameUpdateRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GameUpdateRequest();
    }

    public function test_request_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GameUpdateRequest::class, $this->request);
    }

    public function test_request_has_rules_method(): void
    {
        $rules = $this->request->rules();
        $this->assertIsArray($rules);
    }

    public function test_request_has_authorize_method(): void
    {
        $authorize = $this->request->authorize();
        $this->assertIsBool($authorize);
        $this->assertTrue($authorize);
    }

    public function test_request_has_messages_method(): void
    {
        $hasMessagesMethod = method_exists($this->request, 'messages');
        $this->assertTrue($hasMessagesMethod, 'Request should have messages method');

        if ($hasMessagesMethod) {
            $messages = $this->request->messages();
            $this->assertIsArray($messages, 'Messages method should return an array');
        }
    }

    public function test_rules_contain_update_fields(): void
    {
        $rules = $this->request->rules();

        $this->assertArrayHasKey('date', $rules);
        $this->assertArrayHasKey('season', $rules);
        $this->assertArrayHasKey('status', $rules);
        $this->assertArrayHasKey('home_team_score', $rules);
        $this->assertArrayHasKey('visitor_team_score', $rules);
        $this->assertArrayHasKey('postponed', $rules);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $messages = $this->request->messages();

        $this->assertArrayHasKey('date.required', $messages);
        $this->assertArrayHasKey('season.required', $messages);
        $this->assertArrayHasKey('status.required', $messages);
        $this->assertEquals('O campo data é obrigatório.', $messages['date.required']);
        $this->assertEquals('O campo temporada é obrigatório.', $messages['season.required']);
        $this->assertEquals('O campo status é obrigatório.', $messages['status.required']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

