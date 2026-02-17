<?php

namespace Tests\Unit\Transformers;

use Tests\TestCase;
use App\Transformers\UserTransformer;
use App\Entities\User;
use Mockery;

class UserTransformerTest extends TestCase
{
    protected $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new UserTransformer();
    }

    public function test_transformer_can_be_instantiated(): void
    {
        $this->assertInstanceOf(UserTransformer::class, $this->transformer);
    }

    public function test_transform_method_accepts_user_entity(): void
    {
        $model = Mockery::mock(User::class);

        $reflection = new \ReflectionMethod(UserTransformer::class, 'transform');
        $parameters = $reflection->getParameters();
        $expectedType = $parameters[0]->getType()->getName();

        $this->assertEquals(User::class, $expectedType);
        $this->assertInstanceOf($expectedType, $model);
    }

    public function test_transform_returns_correct_structure(): void
    {
        // Using a real User instance
        $user = new User();
        $user->id = 1;
        $user->name = 'John Doe';
        $user->email = 'john@example.com';
        $user->user_type_id = 1;

        $result = $this->transformer->transform($user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals(1, $result['id']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

