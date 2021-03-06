<?php

namespace Belt\Core\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Core\Role;
use Belt\Core\Http\Requests;

class RolesController extends ApiController
{

    /**
     * The repository instance.
     *
     * @var Role
     */
    public $roles;

    /**
     * ApiController constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->roles = $role;
    }

    public function get($id)
    {
        return $this->roles->find($id) ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Requests\PaginateRoles $request)
    {
        $this->authorize('index', Role::class);

        $paginator = $this->paginator($this->roles->query(), $request->reCapture());

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreRole $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\StoreRole $request)
    {
        $this->authorize('create', Role::class);

        $input = $request->all();

        $role = $this->roles->create(['name' => $input['name']]);

        $this->set($role, $input, [
            'slug',
        ]);

        $role->save();

        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $role = $this->get($id);

        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateRole $request
     * @param  string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\UpdateRole $request, $id)
    {
        $role = $this->get($id);

        $this->authorize('update', $role);

        $input = $request->all();

        $this->set($role, $input, [
            'name',
            'slug',
        ]);

        $role->save();

        return response()->json($role);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->get($id);

        $this->authorize('delete', $role);

        $role->delete();

        return response()->json(null, 204);
    }
}
