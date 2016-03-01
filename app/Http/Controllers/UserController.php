<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRequest;
use App\Repositories\UserRepository;
use App\Http\Requests;

class UserController extends Controller
{

    protected $user;

    public function __construct(UserRepository $user) {
        $this->user = $user;
    }

    /**
     * Show list users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $users = $this->user->paginate(10);
        $title = "List users";
        return view('users.index', compact('users', 'title'));
    }

    /**
     * Create new a user
     */
    public function create() {
        $title = "Create new a user";
        return view('users.create_edit', compact('title'));
    }

    /**
     * Store new record
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request) {
        $this->user->create($request->all());
        return redirect()->route('user.index')->with('success_message', 'The user has been created');
    }

    /**
     * Edit the user info
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id) {
        $user = $this->user->find($id);
        $title = "Edit user: " . $user->name;
        return view('users.create_edit', compact('user', 'title'));
    }

    /**
     * Update the user info
     *
     * @param $id
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UserRequest $request) {
        $user = $this->user->find($id);
        $this->user->update($user, $request->all());
        return redirect(route('user.index', $id))->with('success_message', 'The user has been updated');
    }

    /**
     * Delete a user
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $this->user->delete($id);
        return redirect()->back()->with('success_message', 'The user has been deleted');
    }

}
