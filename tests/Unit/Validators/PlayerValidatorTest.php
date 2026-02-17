<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Validators\PlayerValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

class PlayerValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $validatorFactory = app(ValidatorFactory::class);
        $this->validator = new PlayerValidator($validatorFactory);
    }

    public function test_validator_can_be_instantiated(): void
    {
        $this->assertInstanceOf(PlayerValidator::class, $this->validator);
        $this->assertInstanceOf(LaravelValidator::class, $this->validator);
    }

    public function test_validator_has_create_rules(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $this->assertArrayHasKey(ValidatorInterface::RULE_CREATE, $rules);
        $this->assertIsArray($rules[ValidatorInterface::RULE_CREATE]);
    }

    public function test_create_rules_contain_required_fields(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertArrayHasKey('first_name', $createRules);
        $this->assertArrayHasKey('last_name', $createRules);
        $this->assertArrayHasKey('position', $createRules);
        $this->assertArrayHasKey('team_id', $createRules);
    }

    public function test_validator_has_messages(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertArrayHasKey('first_name.required', $messages);
        $this->assertArrayHasKey('last_name.required', $messages);
        $this->assertEquals('O campo primeiro nome é obrigatório.', $messages['first_name.required']);
        $this->assertEquals('O campo sobrenome é obrigatório.', $messages['last_name.required']);
    }

    public function test_name_fields_validation_rules(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('string', $createRules['first_name']);
        $this->assertStringContainsString('required', $createRules['first_name']);
        $this->assertStringContainsString('string', $createRules['last_name']);
        // last_name is not required, only string and max
    }

    public function test_team_id_field_has_exists_validation(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('exists:teams,id', $createRules['team_id']);
    }

    public function test_draft_fields_are_nullable_integers(): void
    {
        $reflection = new \ReflectionClass(PlayerValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('nullable', $createRules['draft_year']);
        $this->assertStringContainsString('integer', $createRules['draft_year']);
        $this->assertStringContainsString('nullable', $createRules['draft_round']);
        $this->assertStringContainsString('integer', $createRules['draft_round']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

