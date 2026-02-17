<?php

namespace Tests\Unit\Transformers;

use Tests\TestCase;
use App\Transformers\PlayerTransformer;
use App\Entities\Player;
use Mockery;

class PlayerTransformerTest extends TestCase
{
    protected $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new PlayerTransformer();
    }

    public function test_transformer_can_be_instantiated(): void
    {
        $this->assertInstanceOf(PlayerTransformer::class, $this->transformer);
    }

    public function test_transform_method_accepts_player_entity(): void
    {
        $model = Mockery::mock(Player::class);

        $reflection = new \ReflectionMethod(PlayerTransformer::class, 'transform');
        $parameters = $reflection->getParameters();
        $expectedType = $parameters[0]->getType()->getName();

        $this->assertEquals(Player::class, $expectedType);
        $this->assertInstanceOf($expectedType, $model);
    }

    public function test_transform_returns_correct_structure(): void
    {
        // Using a real Player instance
        $player = new Player();
        $player->id = 1;
        $player->first_name = 'LeBron';
        $player->last_name = 'James';
        $player->position = 'F';
        $player->height = '6-9';
        $player->weight = '250';
        $player->jersey_number = '23';
        $player->college = 'St. Vincent-St. Mary HS';
        $player->country = 'USA';
        $player->draft_year = 2003;
        $player->draft_round = 1;
        $player->draft_number = 1;
        $player->team_id = 14;
        $player->created_at = \Carbon\Carbon::now();
        $player->updated_at = \Carbon\Carbon::now();

        $result = $this->transformer->transform($player);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('position', $result);
        $this->assertArrayHasKey('height', $result);
        $this->assertArrayHasKey('weight', $result);
        $this->assertArrayHasKey('jersey_number', $result);
        $this->assertArrayHasKey('college', $result);
        $this->assertArrayHasKey('country', $result);
        $this->assertArrayHasKey('draft_year', $result);
        $this->assertArrayHasKey('draft_round', $result);
        $this->assertArrayHasKey('draft_number', $result);
        $this->assertArrayHasKey('team_id', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('LeBron', $result['first_name']);
        $this->assertEquals('James', $result['last_name']);
        $this->assertEquals('F', $result['position']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

