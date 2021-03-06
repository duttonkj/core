<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Pagination\BaseLengthAwarePaginator;
use Belt\Core\Pagination\IsActiveQueryModifier;
use Illuminate\Database\Eloquent\Model;

class BaseLengthAwarePaginatorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Core\Pagination\BaseLengthAwarePaginator::__construct
     * @covers \Belt\Core\Pagination\BaseLengthAwarePaginator::build
     * @covers \Belt\Core\Pagination\BaseLengthAwarePaginator::toArray
     * @covers \Belt\Core\Pagination\BaseLengthAwarePaginator::orderBy
     */
    public function test()
    {
        $model = new BaseLengthAwarePaginatorModelStub();

        $qb = $model->newQuery();

        $request = new PaginateRequest([
            'q' => 'test',
            'perPage' => 25,
            'page' => 2,
            'orderBy' => 'test.name',
            'sortBy' => 'desc',
        ]);
        $request->searchable[] = 'test.id';
        $request->searchable[] = 'test.name';
        $request->sortable[] = 'test.name';
        $request->queryModifiers[] = IsActiveQueryModifier::class;

        $paginator = new BaseLengthAwarePaginator($qb, $request);

        $array = $paginator->toArray();

        $this->assertTrue(isset($array['meta']));
    }

}

class BaseLengthAwarePaginatorModelStub extends Model
{
    public function newQuery()
    {

        $qbMock = m::mock('Illuminate\Database\Eloquent\Builder');
        $qbMock->shouldReceive('where')->once()->with(
            m::on(function (\Closure $closure) {

                $subQBMock = m::mock('Illuminate\Database\Eloquent\Builder');
                $subQBMock->shouldReceive('orWhere')->once()->with('test.id', 'LIKE', '%test%');
                $subQBMock->shouldReceive('orWhere')->once()->with('test.name', 'LIKE', '%test%');

                $closure($subQBMock);

                // return a bool here so Mockery knows expectation passed
                return is_callable($closure);
            })
        );

        $qbMock->shouldReceive('orderBy')->once()->with('test.name', 'desc');
        $qbMock->shouldReceive('count')->once()->andReturn(1000);
        $qbMock->shouldReceive('take')->once()->with(25);
        $qbMock->shouldReceive('offset')->once()->with(25);
        $qbMock->shouldReceive('get')->once();

        return $qbMock;
    }

}