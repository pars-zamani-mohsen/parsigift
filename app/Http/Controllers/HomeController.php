<?php

namespace App\Http\Controllers;

use App\DailyQuery;
use App\User;
use App\Gift;
use App\Query;
use App\GiftRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $current_user = Auth::user();
        if ($current_user->role == 'user') {
            $date = Date('Y-m-d');
            $dailyQuery = DailyQuery::where('user_id' , $current_user->id)
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->get();

            if (!count($dailyQuery)) {
                $querys = Query::active()->inRandomOrder()->limit(10)->get();
                foreach ($querys as $query) {
                    $instance = new DailyQuery();
                    $instance->user_id = $current_user->id;
                    $instance->query_id = $query->id;
                    $instance->save();
                }
            }

            $dailyQuery = DailyQuery::where('user_id' , $current_user->id)
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->get();
            $data = array(
                'pending_dailyQuery' => count($dailyQuery),
                'dailyQuery' => $dailyQuery,
                'users' => User::all()->count(),
            );

        } else {
            $GiftRequest = new GiftRequest();
            $data = array(
                'gift' => Query::all()->count(),
                'request' => GiftRequest::all()->count(),
                'users' => User::all()->count(),
                'gift_request' => $GiftRequest->fetchAll_paginate(10),
            );
        }

        return view('manager.home', $data);
    }

    /**
     * @return string
     */
    public static function fetch_manager_pre_url()
    {
        return '_manager';
    }

    /**
     * @return string
     */
    public static function fetch_manager_pre_path()
    {
        return 'manager';
    }
}
