<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\Player;
use App\Entities\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;

class PlayerTest extends TestCase
{
    protected $player;

    protected function setUp(): void
    {
        parent::setUp();
        $this->player = new Player();
    }

    public function test_entity_can_be_instantiated(): void
    {
        $this->assertInstanceOf(Player::class, $this->player);
    }

    public function test_entity_implements_transformable(): void
    {
        $this->assertInstanceOf(Transformable::class, $this->player);
    }

    public function test_entity_uses_soft_deletes(): void
    {
        $traits = class_uses(Player::class);
        $this->assertContains(SoftDeletes::class, $traits);
    }

    public function test_entity_has_fillable_attributes(): void
    {
        $fillable = $this->player->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('first_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('position', $fillable);
        $this->assertContains('height', $fillable);
        $this->assertContains('weight', $fillable);
        $this->assertContains('jersey_number', $fillable);
        $this->assertContains('college', $fillable);
        $this->assertContains('country', $fillable);
        $this->assertContains('draft_year', $fillable);
        $this->assertContains('draft_round', $fillable);
        $this->assertContains('draft_number', $fillable);
        $this->assertContains('team_id', $fillable);
    }

    public function test_entity_has_team_relationship(): void
    {
        $reflection = new \ReflectionMethod(Player::class, 'team');
        $this->assertTrue($reflection->isPublic());

        $player = new Player();
        $relation = $player->team();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('team_id', $relation->getForeignKeyName());
    }

    public function test_entity_has_dates_array(): void
    {
        $reflection = new \ReflectionClass(Player::class);
        $property = $reflection->getProperty('dates');
        $property->setAccessible(true);
        $dates = $property->getValue($this->player);

        $this->assertIsArray($dates);
        $this->assertContains('created_at', $dates);
        $this->assertContains('updated_at', $dates);
        $this->assertContains('deleted_at', $dates);
    }

    public function test_entity_can_fill_attributes(): void
    {
        $data = [
            'first_name' => 'LeBron',
            'last_name' => 'James',
            'position' => 'F',
            'height' => '6-9',
            'weight' => '250',
            'jersey_number' => '23',
            'team_id' => 14
        ];

        $player = new Player($data);

        $this->assertEquals('LeBron', $player->first_name);
        $this->assertEquals('James', $player->last_name);
        $this->assertEquals('F', $player->position);
        $this->assertEquals('6-9', $player->height);
        $this->assertEquals('250', $player->weight);
        $this->assertEquals('23', $player->jersey_number);
        $this->assertEquals(14, $player->team_id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

