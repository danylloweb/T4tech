<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\TeamRepositoryEloquent;
use App\Entities\Team;
use App\Validators\TeamValidator;
use App\Presenters\TeamPresenter;
use Mockery;
use Illuminate\Container\Container;

class TeamRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $app = Container::getInstance();
        $this->repository = new TeamRepositoryEloquent($app);
    }

    public function test_model()
    {
        $result = $this->repository->model();
        $this->assertEquals(Team::class, $result);
    }

    public function test_validator()
    {
        $result = $this->repository->validator();
        $this->assertEquals(TeamValidator::class, $result);
    }

    public function test_presenter()
    {
        $result = $this->repository->presenter();
        $this->assertEquals(TeamPresenter::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

