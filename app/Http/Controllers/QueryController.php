<?php

namespace App\Http\Controllers;

use App\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new Query();
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validate_data = array(
            'title' => 'required|string',
            'url' => 'required|string',
        );
        $this->validate($request, $validate_data);

        $this->instance->title = $request->title;
        $this->instance->url = $request->url;
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
        $validate_data = array(
            'title' => ['required', 'string'],
            'url' => ['required', 'string']
        );
        $this->validate($request, $validate_data);

        $instance = $this->instance->_find($id);
        if ($instance) {
            /* Edit instance */
            $instance->title = $request['title'];
            $instance->url = $request['url'];
            $instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
            $result = $instance->save();

            if ($result) {
                return $this->function_response(201);
            }

            return $this->function_response(204);
        }

        return $this->function_response(404);
    }
}
