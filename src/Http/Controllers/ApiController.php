<?php

namespace Belt\Core\Http\Controllers;

use Belt\Core\Http\Exceptions;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Pagination\BaseLengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;

/**
 * Class ApiController
 * @package Belt\Core\Http\Controllers
 */
class ApiController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $statusCode
     * @param string $message
     * @throws Exceptions\ApiException
     * @throws Exceptions\ApiNotFoundHttpException
     */
    public function abort($statusCode, $message = '')
    {
        if ($statusCode == 404) {
            throw new Exceptions\ApiNotFoundHttpException($message);
        }

        $exception = new Exceptions\ApiException();
        $exception->setStatusCode($statusCode);
        $exception->setMsg($message);

        throw $exception;
    }

    /**
     * @param $class
     * @param array $query
     * @return mixed
     * @throws \Exception
     */
    public function getPaginateRequest($class = PaginateRequest::class, $query = [])
    {

        if ($class == PaginateRequest::class || is_subclass_of($class, PaginateRequest::class)) {
            return new $class($query);
        }

        throw new \Exception('Invalid class for ApiController::getPaginateRequest');
    }

    /**
     * @param Builder $qb
     * @param PaginateRequest $request
     * @return BaseLengthAwarePaginator
     */
    public function paginator(Builder $qb, PaginateRequest $request)
    {
        return new BaseLengthAwarePaginator($qb, $request);
    }

    /**
     * @param $item
     * @param $input
     * @param $key
     */
    public function set($item, $input, $key)
    {
        if (is_array($key)) {
            foreach ($key as $_key) {
                $this->set($item, $input, $_key);
            }
        } elseif (array_key_exists($key, $input)) {
            $item->$key = $input[$key];
        }
    }

}