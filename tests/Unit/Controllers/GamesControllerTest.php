<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\GamesController;
use App\Services\GameService;
use App\Validators\GameValidator;
use Mockery;
use Illuminate\Http\Request;

class GamesControllerTest extends TestCase
{
    protected $controller;
    protected $service;
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(GameService::class);
        $this->validator = Mockery::mock(GameValidator::class);
        $this->controller = new GamesController($this->service, $this->validator);
    }

    public function test_controller_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GamesController::class, $this->controller);
    }

    public function test_index_method_returns_json_response(): void
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->with('limit', 15)
            ->andReturn(15);

        $request->shouldReceive('fullUrl')
            ->andReturn('http://localhost/games');

        $expectedData = [
            'data' => [
                [
                    'id' => 1,
                    'date' => '2024-01-01',
                    'season' => 2024,
                    'status' => 'Final'
                ]
            ],
            'total' => 1
        ];

        $this->service->shouldReceive('all')
            ->once()
            ->with(15)
            ->andReturn($expectedData);

        // Mock Cache
        $cacheStore = Mockery::mock(\Illuminate\Contracts\Cache\Store::class);
        $cacheStore->shouldReceive('tags')
            ->with('games')
            ->andReturnSelf();
        $cacheStore->shouldReceive('remember')
            ->andReturnUsing(function($key, $ttl, $callback) {
                return $callback();
            });

        \Illuminate\Support\Facades\Cache::shouldReceive('store')
            ->with('redis')
            ->andReturn($cacheStore);

        $response = $this->controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function test_store_method_creates_game(): void
    {
        $requestData = [
            'date' => '2024-01-01',
            'season' => 2024,
            'status' => 'Final',
            'home_team_score' => 100,
            'visitor_team_score' => 95,
            'home_team_id' => 1,
            'visitor_team_id' => 2
        ];

        $request = Mockery::mock(\App\Http\Requests\GameCreateRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn($requestData);

        $this->service->shouldReceive('create')
            ->once()
            ->with($requestData)
            ->andReturn($requestData);

        $response = $this->controller->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function test_update_method_updates_game(): void
    {
        $id = 1;
        $requestData = [
            'status' => 'Postponed',
            'postponed' => true
        ];

        $request = Mockery::mock(\App\Http\Requests\GameUpdateRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn($requestData);

        $this->service->shouldReceive('update')
            ->once()
            ->with($requestData, $id)
            ->andReturn($requestData);

        $response = $this->controller->update($request, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

