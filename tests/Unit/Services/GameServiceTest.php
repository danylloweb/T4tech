<?php

namespace Tests\Unit\Services;

use App\Criterias\AppRequestCriteria;
use App\Repositories\GameRepository;
use App\Services\GameService;
use App\Integrations\BallDontLieIntegration;
use Tests\TestCase;
use Mockery;

class GameServiceTest extends TestCase
{
    protected $gameService;
    protected $repository;
    protected $integration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(GameRepository::class);
        $this->integration = Mockery::mock(BallDontLieIntegration::class);
        $this->gameService = new GameService($this->repository, $this->integration);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_should_return_all_games_paginated()
    {
        $limit = 20;
        $expectedResult = [
            'data' => [
                [
                    'id' => 1,
                    'date' => '2024-01-01',
                    'season' => 2024,
                    'status' => 'Final',
                    'home_team_score' => 100,
                    'visitor_team_score' => 95
                ],
                [
                    'id' => 2,
                    'date' => '2024-01-02',
                    'season' => 2024,
                    'status' => 'Final',
                    'home_team_score' => 110,
                    'visitor_team_score' => 105
                ]
            ],
            'total' => 2,
            'per_page' => 20,
            'current_page' => 1
        ];

        $this->repository->shouldReceive('resetCriteria')
            ->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('pushCriteria')
            ->once()
            ->with(Mockery::type(AppRequestCriteria::class))
            ->andReturn($this->repository);

        $this->repository->shouldReceive('paginate')
            ->once()
            ->with($limit)
            ->andReturn($expectedResult);

        $result = $this->gameService->all($limit);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_create_method()
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

        $this->repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($data);

        $result = $this->gameService->create($data);

        $this->assertEquals($data, $result);
    }

    public function test_update_method()
    {
        $id = 1;
        $data = [
            'status' => 'Postponed',
            'postponed' => true
        ];

        $this->repository->shouldReceive('update')
            ->once()
            ->with($data, $id)
            ->andReturn($data);

        $result = $this->gameService->update($data, $id);

        $this->assertEquals($data, $result);
    }

    public function test_find_method()
    {
        $id = 1;
        $expectedGame = [
            'id' => 1,
            'date' => '2024-01-01',
            'season' => 2024,
            'status' => 'Final',
            'home_team_score' => 100,
            'visitor_team_score' => 95
        ];

        $this->repository->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($expectedGame);

        $result = $this->gameService->find($id);

        $this->assertEquals($expectedGame, $result);
    }

    public function test_delete_method()
    {
        $id = 1;

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->gameService->delete($id);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }

    public function test_import_games_success()
    {
        $season = 2024;
        $mockResponse = [
            'data' => [
                [
                    'id' => 1,
                    'date' => '2024-01-01',
                    'season' => 2024,
                    'status' => 'Final',
                    'period' => 4,
                    'time' => 'Final',
                    'postseason' => false,
                    'postponed' => false,
                    'home_team_score' => 100,
                    'visitor_team_score' => 95,
                    'datetime' => '2024-01-01T19:00:00.000Z',
                    'home_q1' => 25,
                    'home_q2' => 30,
                    'home_q3' => 20,
                    'home_q4' => 25,
                    'visitor_q1' => 20,
                    'visitor_q2' => 25,
                    'visitor_q3' => 25,
                    'visitor_q4' => 25,
                    'home_team' => ['id' => 1],
                    'visitor_team' => ['id' => 2]
                ]
            ],
            'meta' => [
                'next_cursor' => null
            ]
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', Mockery::pattern('/games\?seasons\[\]=2024&per_page=100/'))
            ->andReturn($mockResponse);

        $this->repository->shouldReceive('skipPresenter')
            ->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('create')
            ->once()
            ->andReturn($mockResponse['data'][0]);

        $result = $this->gameService->importGames($season);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('imported', $result);
        $this->assertEquals(1, $result['imported']);
    }

    public function test_import_games_with_api_error()
    {
        $season = 2024;
        $errorResponse = [
            'error' => true,
            'message' => 'API Error'
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', Mockery::pattern('/games\?seasons\[\]=2024&per_page=100/'))
            ->andReturn($errorResponse);

        $result = $this->gameService->importGames($season);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertNotEmpty($result['errors']);
    }
}

