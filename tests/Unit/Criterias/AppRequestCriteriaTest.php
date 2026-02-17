<?php

namespace Tests\Unit\Criterias;

use App\Criterias\AppRequestCriteria;
use Illuminate\Http\Request;
use Mockery;
use Prettus\Repository\Contracts\RepositoryInterface;
use Tests\TestCase;

class AppRequestCriteriaTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_criteria_can_be_instantiated()
    {
        $request = new Request();
        $criteria = new AppRequestCriteria($request);

        $this->assertInstanceOf(AppRequestCriteria::class, $criteria);
    }

    public function test_apply_criteria_with_search_parameter()
    {
        $request = Request::create('/test', 'GET', [
            'search' => 'test'
        ]);

        $criteria = new AppRequestCriteria($request);

        $model = Mockery::mock(\Illuminate\Database\Eloquent\Model::class);
        $model->shouldReceive('getTable')->andReturn('test_table');

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnUsing(function($closure) use ($query) {
            $closure($query);
            return $query;
        });
        $query->shouldReceive('where')->withArgs(function($field, $operator, $value) {
            return is_string($field);
        })->andReturnSelf();
        $query->shouldReceive('getModel')->andReturn($model);
        $query->shouldReceive('orWhere')->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);
        $repository->shouldReceive('getFieldsSearchable')->andReturn(['name' => 'like']);
        $repository->shouldReceive('getFieldsRules')->andReturn([]);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_criteria_with_order_by()
    {
        $request = Request::create('/test', 'GET', [
            'orderBy' => 'name',
            'sortedBy' => 'desc'
        ]);

        $criteria = new AppRequestCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('orderBy')->with('name', 'desc')->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);
        $repository->shouldReceive('getFieldsSearchable')->andReturn([]);
        $repository->shouldReceive('getFieldsRules')->andReturn([]);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_criteria_with_filter()
    {
        $request = Request::create('/test', 'GET', [
            'filter' => 'status:active'
        ]);

        $criteria = new AppRequestCriteria($request);

        $model = Mockery::mock(\Illuminate\Database\Eloquent\Model::class);
        $model->shouldReceive('getTable')->andReturn('test_table');

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('getModel')->andReturn($model);
        $query->shouldReceive('select')->with(['status:active'])->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);
        $repository->shouldReceive('getFieldsSearchable')->andReturn([]);
        $repository->shouldReceive('getFieldsRules')->andReturn([]);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_criteria_without_parameters()
    {
        $request = Request::create('/test', 'GET');

        $criteria = new AppRequestCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);

        $repository = Mockery::mock(RepositoryInterface::class);
        $repository->shouldReceive('getFieldsSearchable')->andReturn([]);
        $repository->shouldReceive('getFieldsRules')->andReturn([]);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }
}
