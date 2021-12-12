<?php

namespace App\Http\Controllers;

use App\User;
use App\Gift;
use App\Query;
use App\DailyGift;
use App\DailyQuery;
use App\GiftRequest;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

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
                ->where('user_id', $current_user->id)
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
     *
     * @param string|null $date
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report(string $date = null)
    {
        $date = ($date) ? date('Y-m-d', Date::shamsiToTimestamp($date)) : date('Y-m-d');
        $current_user = Auth::user();
        $response = $this->report_generator($current_user, $date);
        return view('manager.report', $response);
    }

    /**
     * Report generator
     *
     * @param $current_user
     * @param string $date
     * @return array
     */
    public function report_generator($current_user, string $date)
    {
        try {
            $response = array('date' => $date, 'current_user' => $current_user);

            if ($current_user->role == "admin") {
                $users = array();
                $success_query = 0;
                $pending_query = 0;
                $success_query_list = array();
                $pending_query_list = array();
                $users_success_query_list = array();
                $users_pending_query_list = array();
                $dailyQuerys = DailyQuery::select('id', 'user_id', 'query_id', 'status')
                    ->with(['_query'])
                    ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                    ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                    ->get();

                foreach ($dailyQuerys as $_dailyQuery) {
                    if ($_dailyQuery->status) {
                        $success_query_list[$_dailyQuery->query_id][] = $_dailyQuery;
                        ++$success_query;
                    } else {
                        $pending_query_list[$_dailyQuery->query_id][] = $_dailyQuery;
                        ++$pending_query;
                    }

                    $users[$_dailyQuery->user_id][] = $_dailyQuery->status;
                }

                foreach ($users as $key => $_user) {
                    if (in_array('0', $_user)) {
                        $users_pending_query_list[] = $key;
                    } else {
                        $users_success_query_list[] = $key;
                    }
                }

                $all_user = User::where('role', 'user')->count();
                $all_user_active = User::where('role', 'user')->where('active', '1')->where('r_and_d_check', '1')->count();
                $all_user_deactive = User::where('role', 'user')->where('active', '0')->orWhere('r_and_d_check', '0')->count();

                $response = array(
                    'date' => $date,
                    'current_user' => $current_user,

                    'today_query' => array('count' => count($dailyQuerys), 'percent' => 100),
                    'success_query' => array('count' => $success_query, 'percent' => number_format(($success_query * 100) / count($dailyQuerys), 2)),
                    'pending_query' => array('count' => $pending_query, 'percent' => number_format(($pending_query * 100) / count($dailyQuerys), 2)),

                    'all_users_query_list' => array('count' => count($users), 'percent' => 100, 'value' => array_keys($users)),
                    'users_success_query_list' => array('count' => count($users_success_query_list), 'percent' => number_format((count($users_success_query_list) * 100) / count($users), 2), 'value' => $users_success_query_list),
                    'users_pending_query_list' => array('count' => count($users_pending_query_list), 'percent' => number_format((count($users_pending_query_list) * 100) / count($users), 2), 'value' => $users_pending_query_list),

                    'all_user' => array('count' => $all_user, 'percent' => 100),
                    'all_user_active' => array('count' => $all_user_active, 'percent' => number_format(($all_user_active * 100) / $all_user, 2)),
                    'all_user_deactive' => array('count' => $all_user_deactive, 'percent' => number_format(($all_user_deactive * 100) / $all_user, 2)),

                    'success_query_list' => $success_query_list,
                    'pending_query_list' => $pending_query_list,
                );
            }

        } catch (\Exception $e){}

        return $response;
    }

    /**
     * @param string $type
     * @param string $ids
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function tasklist(string $type, string $ids)
    {
        try {
            $type = ($type == 'success') ? 'که همه تسک ها را انجام داده اند' : 'که همه تسک ها را انجام نداده اند';
            $instance = new User();
            $modulename = $instance::$modulename;
            $parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
            return view($parent['path'] . '.' . $modulename['en'] . '.list', array(
                'modulename' => $modulename,
                'title' => ' فهرست ' . $modulename['fa'] . ' ' . $type,
                'all' => User::whereIn('id', explode(',', $ids))->paginate(9999),
                'search' => false,
                'onlylist' => true,
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
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
        $dailyQuery_Count = DailyQuery::where('user_id', $current_user->id)
            ->where('created_at', '>', strtotime($date . ' 00:00:00'))
            ->where('created_at', '<', strtotime($date . ' 23:59:59'))
            ->count();

        /* get latest daily query in query id */
        $fromdate = date('Y-m-d', strtotime('-2 days'));
        $todate = date('Y-m-d', strtotime('-1 days'));
        $last_DailyQuery = DailyQuery::where('user_id', $current_user->id)
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
