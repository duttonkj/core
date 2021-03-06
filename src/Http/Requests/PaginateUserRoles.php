<?php
namespace Belt\Core\Http\Requests;

use Belt\Core\Role;
use Belt\Core\Http\Requests\PaginateRoles as PaginateRequest;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateUserRoles
 * @package Belt\Core\Http\Requests
 */
class PaginateUserRoles extends PaginateRequest
{
    /**
     * @var int
     */
    public $perPage = 5;
    /**
     * @var Role
     */
    public $roles;

    /**
     * @return Role
     */
    public function roles()
    {
        return $this->roles ?: $this->roles = new Role();
    }

    /**
     * @inheritdoc
     */
    public function modifyQuery(Builder $query)
    {
        # show roles associated with user
        if (!$this->get('not')) {
            $query->select(['roles.*']);
            $query->join('user_roles', 'user_roles.role_id', '=', 'roles.id');
            $query->where('user_roles.user_id', $this->get('user_id'));
        }

        # show roles not associated with user
        if ($this->get('not')) {
            $query->select(['roles.*']);
            $query->leftJoin('user_roles', function ($subQB) {
                $subQB->on('user_roles.role_id', '=', 'roles.id');
                $subQB->where('user_roles.user_id', $this->get('user_id'));
            });
            $query->whereNull('user_roles.id');
        }

        return $query;
    }

}