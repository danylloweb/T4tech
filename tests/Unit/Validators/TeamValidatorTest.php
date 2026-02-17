<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Validators\TeamValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

class TeamValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $validatorFactory = app(ValidatorFactory::class);
        $this->validator = new TeamValidator($validatorFactory);
    }

    public function test_validator_can_be_instantiated(): void
    {
        $this->assertInstanceOf(TeamValidator::class, $this->validator);
        $this->assertInstanceOf(LaravelValidator::class, $this->validator);
    }

    public function test_validator_has_create_rules(): void
    {
        $reflection = new \ReflectionClass(TeamValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $this->assertArrayHasKey(ValidatorInterface::RULE_CREATE, $rules);
        $this->assertIsArray($rules[ValidatorInterface::RULE_CREATE]);
    }

    public function test_create_rules_contain_required_fields(): void
    {
        $reflection = new \ReflectionClass(TeamValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertArrayHasKey('conference', $createRules);
        $this->assertArrayHasKey('division', $createRules);
        $this->assertArrayHasKey('city', $createRules);
        $this->assertArrayHasKey('name', $createRules);
        $this->assertArrayHasKey('full_name', $createRules);
        $this->assertArrayHasKey('abbreviation', $createRules);
    }

    public function test_validator_has_messages(): void
    {
        $reflection = new \ReflectionClass(TeamValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $reflection = new \ReflectionClass(TeamValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertArrayHasKey('conference.required', $messages);
        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('full_name.required', $messages);
        $this->assertEquals('O campo conferência é obrigatório.', $messages['conference.required']);
        $this->assertEquals('O campo nome é obrigatório.', $messages['name.required']);
    }

    public function test_all_fields_are_required_strings(): void
    {
        $reflection = new \ReflectionClass(TeamValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('required', $createRules['conference']);
        $this->assertStringContainsString('string', $createRules['conference']);
        $this->assertStringContainsString('required', $createRules['name']);
        $this->assertStringContainsString('string', $createRules['name']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

