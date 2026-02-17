<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\TeamsController;
use App\Services\TeamService;
use App\Validators\TeamValidator;
use Mockery;
use Illuminate\Http\Request;

class TeamsControllerTest extends TestCase
{
    protected $controller;
    protected $service;
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(TeamService::class);
        $this->validator = Mockery::mock(TeamValidator::class);
        $this->controller = new TeamsController($this->service, $this->validator);
    }

    public function test_controller_can_be_instantiated(): void
    {
        $this->assertInstanceOf(TeamsController::class, $this->controller);
    }

    public function test_index_method_returns_json_response(): void
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->with('limit', 15)
            ->andReturn(15);

        $request->shouldReceive('fullUrl')
            ->andReturn('http://localhost/teams');

        $expectedData = [
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Hawks',
                    'full_name' => 'Atlanta Hawks',
                    'city' => 'Atlanta'
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
            ->with('teams')
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

    public function test_store_method_creates_team(): void
    {
        $requestData = [
            'name' => 'Lakers',
            'full_name' => 'Los Angeles Lakers',
            'city' => 'Los Angeles',
            'abbreviation' => 'LAL',
            'conference' => 'West',
            'division' => 'Pacific'
        ];

        $request = Mockery::mock(\App\Http\Requests\TeamCreateRequest::class);
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

    public function test_update_method_updates_team(): void
    {
        $id = 1;
        $requestData = [
            'city' => 'New City',
            'abbreviation' => 'NEW'
        ];

        $request = Mockery::mock(\App\Http\Requests\TeamUpdateRequest::class);
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

