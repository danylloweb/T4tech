<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\User;
use Prettus\Repository\Contracts\Transformable;

class UserTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    public function test_entity_can_be_instantiated(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function test_entity_implements_transformable(): void
    {
        $this->assertInstanceOf(Transformable::class, $this->user);
    }

    public function test_entity_has_fillable_attributes(): void
    {
        $fillable = $this->user->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
        $this->assertContains('user_type_id', $fillable);
    }

    public function test_entity_has_dates_array(): void
    {
        $reflection = new \ReflectionClass(User::class);
        $property = $reflection->getProperty('dates');
        $property->setAccessible(true);
        $dates = $property->getValue($this->user);

        $this->assertIsArray($dates);
        $this->assertContains('created_at', $dates);
        $this->assertContains('updated_at', $dates);
        $this->assertContains('deleted_at', $dates);
    }

    public function test_entity_can_fill_attributes(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'user_type_id' => 1
        ];

        $user = new User($data);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('secret123', $user->password);
        $this->assertEquals(1, $user->user_type_id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

