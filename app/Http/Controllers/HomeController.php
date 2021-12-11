<?php

namespace App\Http\Controllers;

use App\User;
use App\Gift;
use App\Query;
use App\DailyGift;
use App\DailyQuery;
use App\GiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

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

            $dailyQuery = DailyQuery::with('_query')
                ->where('user_id' , $current_user->id)
                ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->limit(10)
                ->orderBy('id', 'Desc')
                ->get();
            $data = array(
                'dailyQuery' => $dailyQuery,
            );

        } else {
            $dailyQueryCount = DailyQuery::where('created_at', '>', strtotime($date . ' 00:00:00'))
                ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                ->where('status', '0')
                ->count();

            $data = array(
                'pending_dailyQuery' => $dailyQueryCount,
                'dailyGift' => DailyGift::all()->count(),
                'users' => User::all()->count(),
            );
        }

        return view('manager.home', $data);
    }

    /**
     * Report my daily query
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
        return view('manager.report');
    }

    /**
     * Report my daily query
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bigGift()
    {
        return view('manager.bigGiftList', array(
            'modulename' => array('en' => '', 'fa' => 'جایزه بزرگ', 'model' => ''),
            'title' => ' فهرست ' . '',
            'all' => DailyGift::with('user')->select('id', 'user_id', 'created_at')->where('amount', '>=', 500000)->paginate(10),
            'search' => false,
            'onlylist' => true,
        ));
    }

    /**
     * register Daily Query
     *
     * @param $current_user
     * @param string $date
     */
    public function registerDailyQuery($current_user, string $date)
    {
        $dailyQuery_Count = DailyQuery::where('user_id' , $current_user->id)
            ->where('created_at', '>', strtotime($date . ' 00:00:00'))
            ->where('created_at', '<', strtotime($date . ' 23:59:59'))
            ->count();

        /* get latest daily query in query id */
        $fromdate = date('Y-m-d', strtotime('-2 days'));
        $todate = date('Y-m-d', strtotime('-1 days'));
        $last_DailyQuery = DailyQuery::where('user_id' , $current_user->id)
            ->where('created_at', '>', strtotime($fromdate . ' 00:00:00'))
            ->where('created_at', '<', strtotime($todate . ' 23:59:59'))
            ->pluck('query_id')->toArray();

        if (!$dailyQuery_Count) {
            $querys = Query::active()->whereNotIn('id', $last_DailyQuery)->inRandomOrder()->limit(10)->get();
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
