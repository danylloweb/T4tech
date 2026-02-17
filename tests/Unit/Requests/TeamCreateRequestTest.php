<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\TeamCreateRequest;

class TeamCreateRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new TeamCreateRequest();
    }

    public function test_request_can_be_instantiated(): void
    {
        $this->assertInstanceOf(TeamCreateRequest::class, $this->request);
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

        $this->assertArrayHasKey('conference', $rules);
        $this->assertArrayHasKey('division', $rules);
        $this->assertArrayHasKey('city', $rules);
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('full_name', $rules);
        $this->assertArrayHasKey('abbreviation', $rules);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $messages = $this->request->messages();

        $this->assertArrayHasKey('conference.required', $messages);
        $this->assertArrayHasKey('division.required', $messages);
        $this->assertArrayHasKey('city.required', $messages);
        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('full_name.required', $messages);
        $this->assertArrayHasKey('abbreviation.required', $messages);
        $this->assertEquals('O campo conferência é obrigatório.', $messages['conference.required']);
        $this->assertEquals('O campo nome é obrigatório.', $messages['name.required']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

