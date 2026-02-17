<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Validators\UserValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

class UserValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $validatorFactory = app(ValidatorFactory::class);
        $this->validator = new UserValidator($validatorFactory);
    }

    public function test_validator_can_be_instantiated(): void
    {
        $this->assertInstanceOf(UserValidator::class, $this->validator);
        $this->assertInstanceOf(LaravelValidator::class, $this->validator);
    }

    public function test_validator_has_create_rules(): void
    {
        $reflection = new \ReflectionClass(UserValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $this->assertArrayHasKey(ValidatorInterface::RULE_CREATE, $rules);
        $this->assertIsArray($rules[ValidatorInterface::RULE_CREATE]);
    }

    public function test_validator_has_update_rules(): void
    {
        $reflection = new \ReflectionClass(UserValidator::class);
        $property = $reflection->getProperty('rules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->validator);

        $this->assertArrayHasKey(ValidatorInterface::RULE_UPDATE, $rules);
        $this->assertIsArray($rules[ValidatorInterface::RULE_UPDATE]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

