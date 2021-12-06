<?php

namespace App\Http\Controllers;

use App\AdditionalClasses\Date;
use App\GiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GiftRequestController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new GiftRequest();
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Display a listing of the resource.
     *
     * Return params can have: "onlylist", "is_related_list", "import", "export" to add or remove buttons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
            'modulename' => $this->modulename,
            'title' => ' فهرست ' . $this->modulename['fa'],
            'search' => true,
            'onlylist' => true,
            'all' => $this->instance->fetchAll_paginate(20),
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return null;
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
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        return null;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        return null;
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
        return null;
    }

    /**
     * Active or deactive the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activation($id)
    {
        return null;
    }

    /**
     * Search
     * @param Request $request Id, title, date and ...
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $query = $this->instance::query();
            $fields = $this->instance::$modulefields;
            $parameter = Date::convertPersianNumToEnglish($request->field);

            if (isset($parameter) && $parameter) {
                if (Date::dateValidate($parameter)) {
                    $fromdate = Date::shamsiDateTimeToTimestamp($parameter . ' 00:00:00');
                    $todate = Date::shamsiDateTimeToTimestamp($parameter . ' 23:59:59');
                    $query = $query->whereBetween('created_at', [$fromdate, $todate]);
                    $query = $query->orWhereBetween('updated_at', [$fromdate, $todate]);

                } else {
                    foreach ($fields as $field) {
                        if (in_array($field, ['created_at', 'updated_at', 'active'])) continue;
                        $query = $query->orwhere($field, 'like',  '%' . $parameter .'%');
                    }
                }

                /* check status */
                if (isset($request->status) && $request->status == "active") $query = $query->active();

                /* return result to view */
                return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
                    'modulename' => $this->modulename,
                    'title' => ' فهرست ' . $this->modulename['fa'],
                    'all' => $query->orderBy('id', 'DESC')->paginate(20),
                    'search' => true,
                    'onlylist' => true,
                ));
            }

            return $this->function_response(400);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }
}
