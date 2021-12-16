<?php

namespace App\Http\Controllers;

use App\ActivityLog;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class ActivityLogController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new ActivityLog();
        $this->modulename   = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
            'modulename' => $this->modulename,
            'title' => ' فهرست ' . $this->modulename['fa'],
            'all' => $this->instance->fetchAll_paginate(20),
            'onlylist' => true,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return $this->function_response(404);
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
        return $this->function_response(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        return $this->function_response(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *@throws \Illuminate\Validation\ValidationException

     */
    public function update(Request $request, $id)
    {
        return $this->function_response(404);
    }

    /**
     * Save log from other model
     *
     * @param string $log_type
     * @param string $model
     * @param int $subject_id
     * @param int $user_id
     * @param $content
     * @return mixed|null
     */
    public static function savelog(string $log_type, string $model, int $subject_id, int $user_id, $content)
    {
        $instance = new ActivityLog();
        $instance->log_type = $log_type;
        $instance->model = $model;
        $instance->subject_id = $subject_id;
        $instance->user_id = $user_id;
        $instance->content = $content;
        $result = $instance->save();
        if ($result) {
            return $instance->id;
        }

        return null;
    }
}
