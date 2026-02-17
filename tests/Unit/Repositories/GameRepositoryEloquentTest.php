<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\GameRepositoryEloquent;
use App\Entities\Game;
use App\Validators\GameValidator;
use App\Presenters\GamePresenter;
use Mockery;
use Illuminate\Container\Container;

class GameRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $app = Container::getInstance();
        $this->repository = new GameRepositoryEloquent($app);
    }

    public function test_model()
    {
        $result = $this->repository->model();
        $this->assertEquals(Game::class, $result);
    }

    public function test_validator()
    {
        $result = $this->repository->validator();
        $this->assertEquals(GameValidator::class, $result);
    }

    public function test_presenter()
    {
        $result = $this->repository->presenter();
        $this->assertEquals(GamePresenter::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

