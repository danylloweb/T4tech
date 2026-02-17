<?php

namespace Tests\Unit\Services;

use App\Criterias\AppRequestCriteria;
use App\Repositories\TeamRepository;
use App\Services\TeamService;
use App\Integrations\BallDontLieIntegration;
use Tests\TestCase;
use Mockery;

class TeamServiceTest extends TestCase
{
    protected $teamService;
    protected $repository;
    protected $integration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(TeamRepository::class);
        $this->integration = Mockery::mock(BallDontLieIntegration::class);
        $this->teamService = new TeamService($this->repository, $this->integration);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_should_return_all_teams_paginated()
    {
        $limit = 20;
        $expectedResult = [
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Hawks',
                    'full_name' => 'Atlanta Hawks',
                    'city' => 'Atlanta',
                    'abbreviation' => 'ATL'
                ],
                [
                    'id' => 2,
                    'name' => 'Celtics',
                    'full_name' => 'Boston Celtics',
                    'city' => 'Boston',
                    'abbreviation' => 'BOS'
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

        $result = $this->teamService->all($limit);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_create_method()
    {
        $data = [
            'name' => 'Lakers',
            'full_name' => 'Los Angeles Lakers',
            'city' => 'Los Angeles',
            'abbreviation' => 'LAL',
            'conference' => 'West',
            'division' => 'Pacific'
        ];

        $this->repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($data);

        $result = $this->teamService->create($data);

        $this->assertEquals($data, $result);
    }

    public function test_update_method()
    {
        $id = 1;
        $data = [
            'city' => 'New City',
            'abbreviation' => 'NEW'
        ];

        $this->repository->shouldReceive('update')
            ->once()
            ->with($data, $id)
            ->andReturn($data);

        $result = $this->teamService->update($data, $id);

        $this->assertEquals($data, $result);
    }

    public function test_find_method()
    {
        $id = 1;
        $expectedTeam = [
            'id' => 1,
            'name' => 'Hawks',
            'full_name' => 'Atlanta Hawks',
            'city' => 'Atlanta'
        ];

        $this->repository->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($expectedTeam);

        $result = $this->teamService->find($id);

        $this->assertEquals($expectedTeam, $result);
    }

    public function test_delete_method()
    {
        $id = 1;

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->teamService->delete($id);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }

    public function test_import_teams_success()
    {
        $mockResponse = [
            'data' => [
                [
                    'id' => 1,
                    'conference' => 'East',
                    'division' => 'Southeast',
                    'city' => 'Atlanta',
                    'name' => 'Hawks',
                    'full_name' => 'Atlanta Hawks',
                    'abbreviation' => 'ATL'
                ]
            ],
            'meta' => [
                'next_cursor' => null
            ]
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', 'teams')
            ->andReturn($mockResponse);

        $this->repository->shouldReceive('skipPresenter')
            ->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('create')
            ->once()
            ->andReturn($mockResponse['data'][0]);

        $result = $this->teamService->importTeams();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('imported', $result);
        $this->assertEquals(1, $result['imported']);
    }

    public function test_import_teams_with_api_error()
    {
        $errorResponse = [
            'error' => true,
            'message' => 'API Error'
        ];

        $this->integration->shouldReceive('send')
            ->once()
            ->with('GET', 'teams')
            ->andReturn($errorResponse);

        $result = $this->teamService->importTeams();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertNotEmpty($result['errors']);
    }
}

