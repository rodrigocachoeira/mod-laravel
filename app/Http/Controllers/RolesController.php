<?php

namespace App\Http\Controllers;

use App\Business\Repositories\RoleRepository;
use App\Filters\RoleFilter;

/**
 * Class RolesController
 *
 * @package App\Http\Controllers
 */
class RolesController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RolesController constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param RoleFilter $roleFilter
     * @return \App\Business\Repositories\Illuminate\Database\Eloquent\Collection
     */
    public function index(RoleFilter $roleFilter)
    {
        return $this->roleRepository->withFilter($roleFilter);
    }

    /**
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Role $role)
    {
        if ($this->roleRepository->update($role->id, request()->all())) {
            return $this->successMessage('/roles');
        }
        return back();
    }

}
