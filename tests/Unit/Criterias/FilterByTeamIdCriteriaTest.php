<?php

namespace Tests\Unit\Criterias;

use App\Criterias\FilterByTeamIdCriteria;
use Illuminate\Http\Request;
use Mockery;
use Prettus\Repository\Contracts\RepositoryInterface;
use Tests\TestCase;

class FilterByTeamIdCriteriaTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_criteria_can_be_instantiated()
    {
        $request = new Request();
        $criteria = new FilterByTeamIdCriteria($request);

        $this->assertInstanceOf(FilterByTeamIdCriteria::class, $criteria);
    }

    public function test_apply_filters_by_team_id_when_numeric()
    {
        $request = Request::create('/test', 'GET', [
            'team_id' => '5'
        ]);

        $criteria = new FilterByTeamIdCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('where')
            ->once()
            ->with('team_id', '5')
            ->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_does_not_filter_when_team_id_is_not_numeric()
    {
        $request = Request::create('/test', 'GET', [
            'team_id' => 'invalid'
        ]);

        $criteria = new FilterByTeamIdCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldNotReceive('where');

        $repository = Mockery::mock(RepositoryInterface::class);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_does_not_filter_when_team_id_is_missing()
    {
        $request = Request::create('/test', 'GET');

        $criteria = new FilterByTeamIdCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldNotReceive('where');

        $repository = Mockery::mock(RepositoryInterface::class);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_filters_with_integer_team_id()
    {
        $request = Request::create('/test', 'GET', [
            'team_id' => 10
        ]);

        $criteria = new FilterByTeamIdCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('where')
            ->once()
            ->with('team_id', 10)
            ->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_apply_filters_with_zero_team_id()
    {
        $request = Request::create('/test', 'GET', [
            'team_id' => 0
        ]);

        $criteria = new FilterByTeamIdCriteria($request);

        $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $query->shouldReceive('where')
            ->once()
            ->with('team_id', 0)
            ->andReturnSelf();

        $repository = Mockery::mock(RepositoryInterface::class);

        $result = $criteria->apply($query, $repository);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }
}

