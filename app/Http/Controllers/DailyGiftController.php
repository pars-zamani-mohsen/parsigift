<?php

namespace App\Http\Controllers;

use App\DailyGift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DailyGiftController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new DailyGift();
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
            if ($current_user->role == 'admin') {
                $all = $this->instance->fetchAll_paginate(20);
                $totalGIft = DailyGift::sum('amount');
            } else {
                $totalGIft = DailyGift::where('user_id', $current_user->id)->sum('amount');
                $all = $this->instance->fetchAll_paginate_with_userid(20, Auth::id());
            }


            return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
                'modulename' => $this->modulename,
                'title' => ' فهرست ' . $this->modulename['fa'],
                'all' => $all,
                'totalGIft' => $totalGIft,
                'search' => ($current_user->role == "admin") ? true : false,
                'onlylist' => true,
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    public function specialGift()
    {
        $date = date('Y-m-d', time()); // strtotime("-1 days") // time()

        $specialGift = DailyGift::where('amount', 500000)
            ->where('created_at', '>', strtotime($date . ' 00:00:00'))
            ->where('created_at', '<', strtotime($date . ' 23:59:59'))
            ->first();

        if (!$specialGift) {

            $dailyGift = DailyGift::with(['user'])
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->inRandomOrder()->first();
            if ($dailyGift) {

                $user = $dailyGift->user;
                /* insert gift */
                $dailyGift = new DailyGift();
                $dailyGift->title = 'هدیه مخصوص پارسی گیفت';
                $dailyGift->amount = 500000;
                $dailyGift->user_id = $user->id;
                $dailyGift->save();
                $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شد به #' . $user->id . '-' . $user->name;

            } else {
                $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شده، لطفا فردا تلاش کنید';
            }

        } else {
            $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شده، لطفا فردا تلاش کنید';
        }

        return $message;
    }
}
