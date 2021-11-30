<?php

namespace App\Http\Controllers;

use App\Gift;
use App\GiftRequest;
use App\User;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $GiftRequest = new GiftRequest();
        return view('manager.home', array(
            'gift' => Gift::all()->count(),
            'request' => GiftRequest::all()->count(),
            'users' => User::all()->count(),
            'gift_request' => $GiftRequest->fetchAll_paginate(10),
        ));
    }

    public static function fetch_manager_pre_url()
    {
        return '_manager';
    }

    public static function fetch_manager_pre_path()
    {
        return 'manager';
    }
}
