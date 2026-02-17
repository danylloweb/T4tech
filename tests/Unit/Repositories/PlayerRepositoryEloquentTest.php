<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\PlayerRepositoryEloquent;
use App\Entities\Player;
use App\Validators\PlayerValidator;
use App\Presenters\PlayerPresenter;
use Mockery;
use Illuminate\Container\Container;

class PlayerRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $app = Container::getInstance();
        $this->repository = new PlayerRepositoryEloquent($app);
    }

    public function test_model()
    {
        $result = $this->repository->model();
        $this->assertEquals(Player::class, $result);
    }

    public function test_validator()
    {
        $result = $this->repository->validator();
        $this->assertEquals(PlayerValidator::class, $result);
    }

    public function test_presenter()
    {
        $result = $this->repository->presenter();
        $this->assertEquals(PlayerPresenter::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

