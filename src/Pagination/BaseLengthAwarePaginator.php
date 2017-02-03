<?php
namespace Ohio\Core\Pagination;

use Ohio\Core\Http\Requests\PaginateRequest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseLengthAwarePaginator
{
    /**
     * @var PaginateRequest
     */
    public $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public $qb;

    /**
     * @var LengthAwarePaginator
     */
    public $paginator;

    public function __construct(Builder $qb, PaginateRequest $request)
    {
        $this->qb = $qb;

        $this->request = $request;

        $this->build();
    }

    public function build()
    {

        $request = $this->request;

        $needle = $request->needle();
        if ($needle && $request->searchable) {
            $this->qb->where(function ($subQB) use ($needle, $request) {
                foreach ($request->searchable as $column) {
                    $subQB->orWhere($column, 'LIKE', "%$needle%");
                }
            });
        }

        $request->modifyQuery($this->qb);

        $this->qb->orderBy($request->orderBy(), $request->sortBy());

        $count = $this->qb->count();

        $this->qb->take($request->perPage());
        $this->qb->offset($request->offset());

        $paginator = new LengthAwarePaginator(
            $request->items($this->qb),
            $count,
            $request->perPage(),
            $request->page()
        );

        $paginator->request = $request;

        $paginator->appends($request->query());

        $this->paginator = $paginator;
    }

    public function toArray()
    {
        $array = $this->paginator->toArray();

        $array['meta']['request'] = $this->request->query();

        return $array;
    }

}