<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\UserTypeRepositoryEloquent;
use App\Entities\UserType;
use App\Validators\UserTypeValidator;
use App\Presenters\UserTypePresenter;
use Mockery;
use Illuminate\Container\Container;

class UserTypeRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $app = Container::getInstance();
        $this->repository = new UserTypeRepositoryEloquent($app);
    }

    public function test_model()
    {
        $result = $this->repository->model();
        $this->assertEquals(UserType::class, $result);
    }

    public function test_validator()
    {
        $result = $this->repository->validator();
        $this->assertEquals(UserTypeValidator::class, $result);
    }

    public function test_presenter()
    {
        $result = $this->repository->presenter();
        $this->assertEquals(UserTypePresenter::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

