<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\PlayerCreateRequest;

class PlayerCreateRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new PlayerCreateRequest();
    }

    public function test_request_can_be_instantiated(): void
    {
        $this->assertInstanceOf(PlayerCreateRequest::class, $this->request);
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

    public function test_rules_contain_required_fields(): void
    {
        $rules = $this->request->rules();

        $this->assertArrayHasKey('first_name', $rules);
        $this->assertArrayHasKey('last_name', $rules);
        $this->assertArrayHasKey('position', $rules);
        $this->assertArrayHasKey('height', $rules);
        $this->assertArrayHasKey('weight', $rules);
        $this->assertArrayHasKey('team_id', $rules);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $messages = $this->request->messages();

        $this->assertArrayHasKey('first_name.required', $messages);
        $this->assertArrayHasKey('last_name.required', $messages);
        $this->assertEquals('O campo primeiro nome é obrigatório.', $messages['first_name.required']);
        $this->assertEquals('O campo sobrenome é obrigatório.', $messages['last_name.required']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

