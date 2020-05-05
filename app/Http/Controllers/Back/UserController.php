<?php

namespace App\Http\Controllers\Back;

use App\ {
    Http\Controllers\Controller,
    Http\Requests\UserUpdateRequest,
    Models\User,
    Repositories\UserRepository
};
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Indexable;

    /**
     * Create a new UserController instance.
     *
     * @param  \App\Repositories\UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;

        $this->table = 'users';
    }

    /**
     * Update "new" field for user.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateSeen(User $user)
    {
        $user->ingoing->delete ();

        return response ()->json ();
    }

    /**
     * Update "valid" field for user.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateValid(User $user)
    {
        $user->valid = true;
        $user->save();

        return response ()->json ();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('back.users.edit', compact('user'));
    }

    public function create()
    {
        return view('back.users.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        User::where('id', $user->id)
        ->update([
            'password' => bcrypt($request->input("password")),
            'name' => $request->input("name"),
            'email' => $request->input("email"),
            'role' => $request->input("role")
        ]);

        return back()->with('user-updated', __('The user has been successfully updated'));
    }

    public function reset(Request $request, User $user)
    {
        User::where('id', $user->id)
        ->update([
            'password' => bcrypt("123456"),
        ]);

        return back()->with('user-updated', __('The user has been successfully updated'));
    }

    /**
     * Remove the user from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete ();

        return response ()->json ();
    }

    public function store(Request $request)
    {
        $this->repository->store($request);

        return back()->with('user-updated', __('The user has been successfully updated'));
    }
}
