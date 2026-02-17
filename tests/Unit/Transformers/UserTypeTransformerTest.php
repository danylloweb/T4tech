<?php

namespace Tests\Unit\Transformers;

use Tests\TestCase;
use App\Transformers\UserTypeTransformer;
use App\Entities\UserType;
use Mockery;

class UserTypeTransformerTest extends TestCase
{
    protected $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new UserTypeTransformer();
    }

    public function test_transformer_can_be_instantiated(): void
    {
        $this->assertInstanceOf(UserTypeTransformer::class, $this->transformer);
    }

    public function test_transform_method_accepts_user_type_entity(): void
    {
        $model = Mockery::mock(UserType::class);

        $reflection = new \ReflectionMethod(UserTypeTransformer::class, 'transform');
        $parameters = $reflection->getParameters();
        $expectedType = $parameters[0]->getType()->getName();

        $this->assertEquals(UserType::class, $expectedType);
        $this->assertInstanceOf($expectedType, $model);
    }

    public function test_transform_returns_correct_structure(): void
    {
        // Using a real UserType instance
        $userType = new UserType();
        $userType->id = 1;
        $userType->name = 'Administrator';
        $userType->description = 'Full system access';

        $result = $this->transformer->transform($userType);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Administrator', $result['name']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

