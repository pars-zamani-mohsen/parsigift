<?php

namespace App\Http\Controllers;

use App\DailyGift;
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
        $date = Date('Y-m-d');
        $current_user = Auth::user();

        if ($current_user->role == 'user') {
            $this->registerDailyQuery($current_user, $date);

            $dailyQueryCount = DailyQuery::where('user_id' , $current_user->id)
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->count();

            $dailyQuery = DailyQuery::with('_query')
                ->where('user_id' , $current_user->id)
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->limit(10)
                ->orderBy('id', 'Desc')
                ->get();
            $data = array(
                'pending_dailyQuery' => $dailyQueryCount,
                'dailyQuery' => $dailyQuery,
                'dailyGift' => DailyGift::where('user_id' , $current_user->id)->count(),
                'users' => User::all()->count(),
                'current_user' => $current_user,
            );

        } else {
            $dailyQueryCount = DailyQuery::where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->count();
            $dailyQuery = DailyQuery::with('_query')
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->limit(10)
                ->orderBy('id', 'Desc')
                ->get();
            $data = array(
                'pending_dailyQuery' => $dailyQueryCount,
                'dailyQuery' => $dailyQuery,
                'dailyGift' => DailyGift::all()->count(),
                'users' => User::all()->count(),
                'current_user' => $current_user,
            );
        }

        return view('manager.home', $data);
    }

    /**
     * register Daily Query
     *
     * @param $current_user
     * @param string $date
     */
    public function registerDailyQuery($current_user, string $date)
    {
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
