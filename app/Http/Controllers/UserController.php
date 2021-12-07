<?php

namespace App\Http\Controllers;

use App\User;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new User();
        $this->modulename   = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validate_data = array(
            'name' => ['required', 'string'],
            'tell' => ['required', 'string', 'max:11', 'unique:users', new Mobile()],
            'password' => ['required', 'string', 'min:6'],
        );
        $this->validate($request, $validate_data);

        $this->instance->name = $request->name;
        $this->instance->tell = $request->tell;
        $this->instance->role = $request->role;
        $this->instance->password = bcrypt($request->password);
        $this->instance->created_by = (Auth::user()) ? Auth::id() : 1;
        $result = $this->instance->save();

        if ($result) {
            return $this->function_response(201);
        }

        return $this->function_response(204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $instance = $this->instance->_find($id);
        if ($instance) {
            $validate_data = array(
                'name' => ['required', 'string'],
                'tell' => ['required', 'string', 'max:11', new Mobile()],
                'password' => ['string', 'min:6'],
            );
            $this->validate($request, $validate_data);

            /* Edit user */
            $instance->name = $request->name;
            $instance->tell = $request->tell;
            $instance->role = $request->role;
            if ($request->password) $instance->password = bcrypt($request->password);
            $result = $instance->save();

            if ($result) {
                return $this->function_response(201);
            }

            return $this->function_response(204);
        }

        return $this->function_response(404);
    }

    /**
     * Clear laravel cache
     *
     * @return string
     */
    public function clear()
    {
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        return 'completed...';
    }
}
