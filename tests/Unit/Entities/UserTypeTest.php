<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\UserType;
use Prettus\Repository\Contracts\Transformable;

class UserTypeTest extends TestCase
{
    protected $userType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userType = new UserType();
    }

    public function test_entity_can_be_instantiated(): void
    {
        $this->assertInstanceOf(UserType::class, $this->userType);
    }

    public function test_entity_implements_transformable(): void
    {
        $this->assertInstanceOf(Transformable::class, $this->userType);
    }

    public function test_entity_has_fillable_attributes(): void
    {
        $fillable = $this->userType->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
    }

    public function test_entity_has_users_relationship(): void
    {
        $reflection = new \ReflectionMethod(UserType::class, 'users');
        $this->assertTrue($reflection->isPublic());

        $userType = new UserType();
        $relation = $userType->users();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
        $this->assertEquals('user_type_id', $relation->getForeignKeyName());
    }

    public function test_entity_can_fill_attributes(): void
    {
        $data = [
            'name' => 'Administrator',
            'description' => 'Full system access'
        ];

        $userType = new UserType($data);

        $this->assertEquals('Administrator', $userType->name);
        $this->assertEquals('Full system access', $userType->description);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

