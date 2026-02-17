<?php

namespace Tests\Unit\Services;

use App\Criterias\AppRequestCriteria;
use App\Repositories\PlayerRepository;
use App\Services\PlayerService;
use App\Integrations\BallDontLieIntegration;
use Tests\TestCase;
use Mockery;

class PlayerServiceTest extends TestCase
{
    protected $playerService;
    protected $repository;
    protected $integration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(PlayerRepository::class);
        $this->integration = Mockery::mock(BallDontLieIntegration::class);
        $this->playerService = new PlayerService($this->repository, $this->integration);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_should_return_all_players_paginated()
    {
        $limit = 20;
        $expectedResult = [
            'data' => [
                [
                    'id' => 1,
                    'first_name' => 'LeBron',
                    'last_name' => 'James',
                    'position' => 'F',
                    'team_id' => 1
                ],
                [
                    'id' => 2,
                    'first_name' => 'Stephen',
                    'last_name' => 'Curry',
                    'position' => 'G',
                    'team_id' => 2
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
            ->with(Mockery::type(\App\Criterias\FilterByTeamIdCriteria::class))
            ->andReturn($this->repository);

        $this->repository->shouldReceive('pushCriteria')
            ->once()
            ->with(Mockery::type(AppRequestCriteria::class))
            ->andReturn($this->repository);

        $this->repository->shouldReceive('paginate')
            ->once()
            ->with($limit)
            ->andReturn($expectedResult);

        $result = $this->playerService->all($limit);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_create_method()
    {
        $data = [
            'first_name' => 'LeBron',
            'last_name' => 'James',
            'position' => 'F',
            'height' => '6-9',
            'weight' => '250',
            'team_id' => 1
        ];

        $this->repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($data);

        $result = $this->playerService->create($data);

        $this->assertEquals($data, $result);
    }

    public function test_update_method()
    {
        $id = 1;
        $data = [
            'position' => 'F-G',
            'jersey_number' => '23'
        ];

        $this->repository->shouldReceive('update')
            ->once()
            ->with($data, $id)
            ->andReturn($data);

        $result = $this->playerService->update($data, $id);

        $this->assertEquals($data, $result);
    }

    public function test_find_method()
    {
        $id = 1;
        $expectedPlayer = [
            'id' => 1,
            'first_name' => 'LeBron',
            'last_name' => 'James',
            'position' => 'F',
            'team_id' => 1
        ];

        $this->repository->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($expectedPlayer);

        $result = $this->playerService->find($id);

        $this->assertEquals($expectedPlayer, $result);
    }

    public function test_delete_method()
    {
        $id = 1;

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->playerService->delete($id);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }

    public function test_import_players_success()
    {
        $mockResponse = [
            'data' => [
                [
                    'id' => 1,
                    'first_name' => 'LeBron',
                    'last_name' => 'James',
                    'position' => 'F',
                    'height' => '6-9',
                    'weight' => '250',
                    'jersey_number' => '23',
                    'college' => 'St. Vincent-St. Mary HS',
                    'country' => 'USA',
                    'draft_year' => 2003,
                    'draft_round' => 1,
                    'draft_number' => 1,
                    'team' => ['id' => 14]
                ]
            ],
            'meta' => [
                'next_cursor' => null
            ]
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', Mockery::pattern('/players\?cursor=25/'))
            ->andReturn($mockResponse);

        $this->repository->shouldReceive('skipPresenter')
            ->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('create')
            ->once()
            ->andReturn($mockResponse['data'][0]);

        $result = $this->playerService->importPlayers();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('imported', $result);
        $this->assertEquals(1, $result['imported']);
    }

    public function test_import_players_with_api_error()
    {
        $errorResponse = [
            'error' => true,
            'message' => 'API Error'
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', Mockery::pattern('/players\?cursor=25/'))
            ->andReturn($errorResponse);

        $result = $this->playerService->importPlayers();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertNotEmpty($result['errors']);
    }
}

