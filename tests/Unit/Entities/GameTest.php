<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\Game;
use App\Entities\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;

class GameTest extends TestCase
{
    protected $game;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = new Game();
    }

    public function test_entity_can_be_instantiated(): void
    {
        $this->assertInstanceOf(Game::class, $this->game);
    }

    public function test_entity_implements_transformable(): void
    {
        $this->assertInstanceOf(Transformable::class, $this->game);
    }

    public function test_entity_uses_soft_deletes(): void
    {
        $traits = class_uses(Game::class);
        $this->assertContains(SoftDeletes::class, $traits);
    }

    public function test_entity_has_fillable_attributes(): void
    {
        $fillable = $this->game->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('date', $fillable);
        $this->assertContains('season', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('period', $fillable);
        $this->assertContains('time', $fillable);
        $this->assertContains('postseason', $fillable);
        $this->assertContains('postponed', $fillable);
        $this->assertContains('home_team_score', $fillable);
        $this->assertContains('visitor_team_score', $fillable);
        $this->assertContains('datetime', $fillable);
        $this->assertContains('home_q1', $fillable);
        $this->assertContains('home_q2', $fillable);
        $this->assertContains('home_q3', $fillable);
        $this->assertContains('home_q4', $fillable);
        $this->assertContains('home_ot1', $fillable);
        $this->assertContains('home_ot2', $fillable);
        $this->assertContains('home_ot3', $fillable);
        $this->assertContains('home_timeouts_remaining', $fillable);
        $this->assertContains('home_in_bonus', $fillable);
        $this->assertContains('visitor_q1', $fillable);
        $this->assertContains('visitor_q2', $fillable);
        $this->assertContains('visitor_q3', $fillable);
        $this->assertContains('visitor_q4', $fillable);
        $this->assertContains('visitor_ot1', $fillable);
        $this->assertContains('visitor_ot2', $fillable);
        $this->assertContains('visitor_ot3', $fillable);
        $this->assertContains('visitor_timeouts_remaining', $fillable);
        $this->assertContains('visitor_in_bonus', $fillable);
        $this->assertContains('ist_stage', $fillable);
        $this->assertContains('home_team_id', $fillable);
        $this->assertContains('visitor_team_id', $fillable);
    }

    public function test_entity_has_home_team_relationship(): void
    {
        $reflection = new \ReflectionMethod(Game::class, 'homeTeam');
        $this->assertTrue($reflection->isPublic());

        $game = new Game();
        $relation = $game->homeTeam();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('home_team_id', $relation->getForeignKeyName());
    }

    public function test_entity_has_visitor_team_relationship(): void
    {
        $reflection = new \ReflectionMethod(Game::class, 'visitorTeam');
        $this->assertTrue($reflection->isPublic());

        $game = new Game();
        $relation = $game->visitorTeam();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('visitor_team_id', $relation->getForeignKeyName());
    }

    public function test_entity_has_dates_array(): void
    {
        $reflection = new \ReflectionClass(Game::class);
        $property = $reflection->getProperty('dates');
        $property->setAccessible(true);
        $dates = $property->getValue($this->game);

        $this->assertIsArray($dates);
        $this->assertContains('created_at', $dates);
        $this->assertContains('updated_at', $dates);
        $this->assertContains('deleted_at', $dates);
    }

    public function test_entity_can_fill_attributes(): void
    {
        $data = [
            'date' => '2024-01-01',
            'season' => 2024,
            'status' => 'Final',
            'home_team_score' => 100,
            'visitor_team_score' => 95,
            'home_team_id' => 1,
            'visitor_team_id' => 2
        ];

        $game = new Game($data);

        $this->assertEquals('2024-01-01', $game->date);
        $this->assertEquals(2024, $game->season);
        $this->assertEquals('Final', $game->status);
        $this->assertEquals(100, $game->home_team_score);
        $this->assertEquals(95, $game->visitor_team_score);
        $this->assertEquals(1, $game->home_team_id);
        $this->assertEquals(2, $game->visitor_team_id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

