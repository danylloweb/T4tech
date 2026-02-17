<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Validators\GameValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

class GameValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $validatorFactory = app(ValidatorFactory::class);
        $this->validator = new GameValidator($validatorFactory);
    }

    public function test_validator_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GameValidator::class, $this->validator);
        $this->assertInstanceOf(LaravelValidator::class, $this->validator);
    }

    public function test_validator_has_create_rules(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $this->assertArrayHasKey(ValidatorInterface::RULE_CREATE, $rules);
        $this->assertIsArray($rules[ValidatorInterface::RULE_CREATE]);
    }

    public function test_create_rules_contain_required_fields(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertArrayHasKey('date', $createRules);
        $this->assertArrayHasKey('season', $createRules);
        $this->assertArrayHasKey('status', $createRules);
        $this->assertArrayHasKey('home_team_score', $createRules);
        $this->assertArrayHasKey('visitor_team_score', $createRules);
        $this->assertArrayHasKey('home_team_id', $createRules);
        $this->assertArrayHasKey('visitor_team_id', $createRules);
    }

    public function test_validator_has_messages(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
    }

    public function test_messages_contain_validation_messages(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->validator);

        $this->assertArrayHasKey('date.required', $messages);
        $this->assertArrayHasKey('season.required', $messages);
        $this->assertArrayHasKey('status.required', $messages);
        $this->assertEquals('O campo data é obrigatório.', $messages['date.required']);
        $this->assertEquals('O campo temporada é obrigatório.', $messages['season.required']);
        $this->assertEquals('O campo status é obrigatório.', $messages['status.required']);
    }

    public function test_date_field_validation_rules(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('date', $createRules['date']);
        $this->assertStringContainsString('required', $createRules['date']);
    }

    public function test_season_field_validation_rules(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('integer', $createRules['season']);
        $this->assertStringContainsString('min:1900', $createRules['season']);
    }

    public function test_team_id_fields_have_exists_validation(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('exists:teams,id', $createRules['home_team_id']);
        $this->assertStringContainsString('exists:teams,id', $createRules['visitor_team_id']);
    }

    public function test_score_fields_are_nullable_integers(): void
    {
        $reflection = new \ReflectionClass(GameValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $createRules = $rules[ValidatorInterface::RULE_CREATE];

        $this->assertStringContainsString('nullable', $createRules['home_team_score']);
        $this->assertStringContainsString('integer', $createRules['home_team_score']);
        $this->assertStringContainsString('nullable', $createRules['visitor_team_score']);
        $this->assertStringContainsString('integer', $createRules['visitor_team_score']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

