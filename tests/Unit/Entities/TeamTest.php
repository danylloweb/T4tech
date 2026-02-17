<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;

class TeamTest extends TestCase
{
    protected $team;

    protected function setUp(): void
    {
        parent::setUp();
        $this->team = new Team();
    }

    public function test_entity_can_be_instantiated(): void
    {
        $this->assertInstanceOf(Team::class, $this->team);
    }

    public function test_entity_implements_transformable(): void
    {
        $this->assertInstanceOf(Transformable::class, $this->team);
    }

    public function test_entity_uses_soft_deletes(): void
    {
        $traits = class_uses(Team::class);
        $this->assertContains(SoftDeletes::class, $traits);
    }

    public function test_entity_has_fillable_attributes(): void
    {
        $fillable = $this->team->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('conference', $fillable);
        $this->assertContains('division', $fillable);
        $this->assertContains('city', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('full_name', $fillable);
        $this->assertContains('abbreviation', $fillable);
    }

    public function test_entity_has_dates_array(): void
    {
        $reflection = new \ReflectionClass(Team::class);
        $property = $reflection->getProperty('dates');
        $property->setAccessible(true);
        $dates = $property->getValue($this->team);

        $this->assertIsArray($dates);
        $this->assertContains('created_at', $dates);
        $this->assertContains('updated_at', $dates);
        $this->assertContains('deleted_at', $dates);
    }

    public function test_entity_can_fill_attributes(): void
    {
        $data = [
            'conference' => 'East',
            'division' => 'Southeast',
            'city' => 'Atlanta',
            'name' => 'Hawks',
            'full_name' => 'Atlanta Hawks',
            'abbreviation' => 'ATL'
        ];

        $team = new Team($data);

        $this->assertEquals('East', $team->conference);
        $this->assertEquals('Southeast', $team->division);
        $this->assertEquals('Atlanta', $team->city);
        $this->assertEquals('Hawks', $team->name);
        $this->assertEquals('Atlanta Hawks', $team->full_name);
        $this->assertEquals('ATL', $team->abbreviation);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

