<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\PlayersController;
use App\Services\PlayerService;
use App\Validators\PlayerValidator;
use Mockery;
use Illuminate\Http\Request;

class PlayersControllerTest extends TestCase
{
    protected $controller;
    protected $service;
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(PlayerService::class);
        $this->validator = Mockery::mock(PlayerValidator::class);
        $this->controller = new PlayersController($this->service, $this->validator);
    }

    public function test_controller_can_be_instantiated(): void
    {
        $this->assertInstanceOf(PlayersController::class, $this->controller);
    }

    public function test_index_method_returns_json_response(): void
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->with('limit', 15)
            ->andReturn(15);

        $request->shouldReceive('fullUrl')
            ->andReturn('http://localhost/players');

        $expectedData = [
            'data' => [
                [
                    'id' => 1,
                    'first_name' => 'LeBron',
                    'last_name' => 'James',
                    'position' => 'F'
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
            ->with('players')
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

    public function test_store_method_creates_player(): void
    {
        $requestData = [
            'first_name' => 'LeBron',
            'last_name' => 'James',
            'position' => 'F',
            'height' => '6-9',
            'weight' => '250',
            'team_id' => 1
        ];

        $request = Mockery::mock(\App\Http\Requests\PlayerCreateRequest::class);
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

    public function test_update_method_updates_player(): void
    {
        $id = 1;
        $requestData = [
            'position' => 'F-G',
            'jersey_number' => '23'
        ];

        $request = Mockery::mock(\App\Http\Requests\PlayerUpdateRequest::class);
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

