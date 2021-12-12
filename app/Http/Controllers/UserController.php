<?php

namespace App\Http\Controllers;

use App\User;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
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
        $this->instance->tell = Date::convertPersianNumToEnglish($request->tell);
        $this->instance->role = $request->role;
        $this->instance->nesbat = $request->nesbat;
        $this->instance->cart_number = Date::convertPersianNumToEnglish($request->cart_number);
        $this->instance->r_and_d_check = $request->r_and_d_check;
        $this->instance->password = bcrypt(Date::convertPersianNumToEnglish($request->password));
        $this->instance->r_and_d_check = (isset($request->r_and_d_check) && $request->r_and_d_check == 'on') ? 1 : 0;
        $this->instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
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
            $instance->tell = Date::convertPersianNumToEnglish($request->tell);
            $instance->role = $request->role;
            $instance->nesbat = $request->nesbat;
            $instance->cart_number = Date::convertPersianNumToEnglish($request->cart_number);
            $instance->r_and_d_check = (isset($request->r_and_d_check) && $request->r_and_d_check == 'on') ? 1 : 0;
            $instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
            if ($request->password) $instance->password = bcrypt(Date::convertPersianNumToEnglish($request->password));
            $result = $instance->save();

            if ($result) {
                return $this->function_response(201);
            }

            return $this->function_response(204);
        }

        return $this->function_response(404);
    }

    /**
     * Check user activity
     *
     * @return string
     */
    public function checkActvity()
    {
        $date = Date('Y-m-d', strtotime('-3 days'));
        $deactive_users = User::with('dailyQuery')
            ->where('role', 'user')
            ->where('created_at', '<', strtotime($date . ' 00:00:00'))
            ->get();

        foreach ($deactive_users as $user) {
            if ($user->dailyQuery && isset($user->dailyQuery->created_at)) {
                $created_time = $user->dailyQuery->toArray()['created_at'];
                if ($created_time < strtotime($date . ' 00:00:00')) {
                    $user->active = 0;
                    $user->save();
                }
            }
        }
        return 'done!';
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
