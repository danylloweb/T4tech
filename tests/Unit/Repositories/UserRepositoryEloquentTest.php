<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\UserRepositoryEloquent;
use App\Entities\User;
use App\Validators\UserValidator;
use App\Presenters\UserPresenter;
use Mockery;
use Illuminate\Container\Container;

class UserRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $app = Container::getInstance();
        $this->repository = new UserRepositoryEloquent($app);
    }

    public function test_model()
    {
        $result = $this->repository->model();
        $this->assertEquals(User::class, $result);
    }

    public function test_validator()
    {
        $result = $this->repository->validator();
        $this->assertEquals(UserValidator::class, $result);
    }

    public function test_presenter()
    {
        $result = $this->repository->presenter();
        $this->assertEquals(UserPresenter::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

