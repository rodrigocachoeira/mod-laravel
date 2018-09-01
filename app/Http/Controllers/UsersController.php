<?php

namespace App\Http\Controllers;

use App\Business\Repositories\UserRepository;
use App\Business\Repositories\RoleRepository;
use App\Filters\UserFilter;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     */
	public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param UserFilter $userFilter
     * @return \App\Business\Repositories\Illuminate\Database\Eloquent\Collection
     */
	public function index(UserFilter $userFilter)
    {
        return $this->userRepository->withFilter($userFilter);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roles = $this->roleRepository->ordered('name', 'ASC');
        return view('users.create', compact('roles'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = $this->roleRepository->ordered('name', 'ASC');
        return view('users.edit', compact('roles', 'user'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        if ($this->userRepository->save(request()->all())) {
            return $this->successMessage('/users');
        }
        return back();
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user)
    {
        if ($this->userRepository->update($user->id, request()->all())) {
            return $this->successMessage('/users');
        }
        return back();
    }

    public function destroy(User $user)
    {}

}
