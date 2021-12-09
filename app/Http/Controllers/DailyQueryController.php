<?php

namespace App\Http\Controllers;

use App\AdditionalClasses\Date;
use App\DailyQuery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DailyQueryController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new DailyQuery();
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Display a listing of the resource.
     *
     * Return params can have: "onlylist", "is_related_list", "search", "import", "export", "shortcode" to add or remove buttons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $current_user = Auth::user();
            return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
                'modulename' => $this->modulename,
                'title' => ' فهرست ' . $this->modulename['fa'],
                'all' => $this->instance->fetchAll_paginate(10),
                'search' => ($current_user->role == "admin") ? true : false,
                'onlylist' => true,
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    public function report($date)
    {
        $current_user = Auth::user();
        $todate = date('Y-m-d', Date::shamsiToTimestamp(str_replace('-', '/', $date)));
        $dailyQuery = DailyQuery::with('_query')
            ->where('user_id' , $current_user->id)
            ->where('created_at', '>', strtotime($todate . ' 00:00:00'))
            ->where('created_at', '<', strtotime($todate . ' 23:59:59'))
            ->orderBy('id', 'Desc')
            ->get()->toArray();

        return ($dailyQuery) ? $dailyQuery : null;
    }
}
