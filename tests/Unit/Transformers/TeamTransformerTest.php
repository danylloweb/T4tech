<?php

namespace Tests\Unit\Transformers;

use Tests\TestCase;
use App\Transformers\TeamTransformer;
use App\Entities\Team;
use Mockery;

class TeamTransformerTest extends TestCase
{
    protected $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new TeamTransformer();
    }

    public function test_transformer_can_be_instantiated(): void
    {
        $this->assertInstanceOf(TeamTransformer::class, $this->transformer);
    }

    public function test_transform_method_accepts_team_entity(): void
    {
        $model = Mockery::mock(Team::class);

        $reflection = new \ReflectionMethod(TeamTransformer::class, 'transform');
        $parameters = $reflection->getParameters();
        $expectedType = $parameters[0]->getType()->getName();

        $this->assertEquals(Team::class, $expectedType);
        $this->assertInstanceOf($expectedType, $model);
    }

    public function test_transform_returns_correct_structure(): void
    {
        // Using a real Team instance
        $team = new Team();
        $team->id = 1;
        $team->conference = 'East';
        $team->division = 'Southeast';
        $team->city = 'Atlanta';
        $team->name = 'Hawks';
        $team->full_name = 'Atlanta Hawks';
        $team->abbreviation = 'ATL';
        $team->created_at = \Carbon\Carbon::now();
        $team->updated_at = \Carbon\Carbon::now();

        $result = $this->transformer->transform($team);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('conference', $result);
        $this->assertArrayHasKey('division', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('abbreviation', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Hawks', $result['name']);
        $this->assertEquals('Atlanta Hawks', $result['full_name']);
        $this->assertEquals('ATL', $result['abbreviation']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

