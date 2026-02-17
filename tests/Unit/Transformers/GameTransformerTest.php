<?php

namespace Tests\Unit\Transformers;

use Tests\TestCase;
use App\Transformers\GameTransformer;
use App\Entities\Game;
use Mockery;

class GameTransformerTest extends TestCase
{
    protected $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new GameTransformer();
    }

    public function test_transformer_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GameTransformer::class, $this->transformer);
    }

    public function test_transform_method_accepts_game_entity(): void
    {
        $model = Mockery::mock(Game::class);

        $reflection = new \ReflectionMethod(GameTransformer::class, 'transform');
        $parameters = $reflection->getParameters();
        $expectedType = $parameters[0]->getType()->getName();

        $this->assertEquals(Game::class, $expectedType);
        $this->assertInstanceOf($expectedType, $model);
    }

    public function test_transform_returns_correct_structure(): void
    {
        // Using a real Game instance instead of mock to avoid setAttribute issues
        $game = new Game();
        $game->id = 1;
        $game->date = '2024-01-01';
        $game->season = 2024;
        $game->status = 'Final';
        $game->period = 4;
        $game->time = 'Final';
        $game->postseason = false;
        $game->postponed = false;
        $game->home_team_score = 100;
        $game->visitor_team_score = 95;
        $game->datetime = '2024-01-01 19:00:00';
        $game->home_q1 = 25;
        $game->home_q2 = 30;
        $game->home_q3 = 20;
        $game->home_q4 = 25;
        $game->home_ot1 = null;
        $game->home_ot2 = null;
        $game->home_ot3 = null;
        $game->home_timeouts_remaining = 2;
        $game->home_in_bonus = false;
        $game->visitor_q1 = 20;
        $game->visitor_q2 = 25;
        $game->visitor_q3 = 25;
        $game->visitor_q4 = 25;
        $game->visitor_ot1 = null;
        $game->visitor_ot2 = null;
        $game->visitor_ot3 = null;
        $game->visitor_timeouts_remaining = 1;
        $game->visitor_in_bonus = false;
        $game->ist_stage = null;
        $game->home_team_id = 1;
        $game->visitor_team_id = 2;
        $game->created_at = \Carbon\Carbon::now();
        $game->updated_at = \Carbon\Carbon::now();

        $result = $this->transformer->transform($game);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('season', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('home_team_score', $result);
        $this->assertArrayHasKey('visitor_team_score', $result);
        $this->assertArrayHasKey('home_team_id', $result);
        $this->assertArrayHasKey('visitor_team_id', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals(2024, $result['season']);
        $this->assertEquals('Final', $result['status']);
        $this->assertEquals(100, $result['home_team_score']);
        $this->assertEquals(95, $result['visitor_team_score']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

