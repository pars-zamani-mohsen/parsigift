<?php

namespace App\Http\Controllers;

use App\DailyGift;
use App\Gift;
use App\GiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * lottery for pars special gift
     *
     * @return string
     */
    public function specialGift()
    {
        $date = date('Y-m-d', strtotime("-1 days")); // strtotime("-1 days") // time()

        $specialGift = $this->dailyGiftSelector($date, true);
        if (!$specialGift) {

            $dailyGift = $this->dailyGiftSelector($date);
            if ($dailyGift) {
                $user = $dailyGift->user;
                /* insert gift */
                $dailyGift = new DailyGift();
                $dailyGift->title = 'هدیه مخصوص پارسی گیفت';
                $dailyGift->amount = DailyGift::$dailySpecialGiftAmount;
                $dailyGift->user_id = $user->id;
                $dailyGift->special = 1;
                $dailyGift->save();
                $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شد به #' . $user->id . '-' . $user->name;
                if($user->nesbat) $message .= " ($user->nesbat)";

                $sms_message = "با سلام، شما برنده خوش شانس جایزه بزرگ ۵۰۰ هزار تومانی امروز پارسی گیفت شدید.
لطفا ظرف چهار ساعت آینده اسکرین شات این پیامک را در گروه هولدینگ به اشتراک بگذارید تا جایزه تان ثبت گردد.
موفق و پیروز باشید.";
                \App\Message::send_simple_sms(\App\Message::getSmsSenderNumber(), [$user->tell], $sms_message);

            } else {
                $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شده، لطفا فردا تلاش کنید';
            }

        } else {
            $message = 'هدیه مخصوص پارسی گیفت امروز اختصاص داده شده، لطفا فردا تلاش کنید';
        }

        $this->sendMessageToBot(base64_encode($message));
        return $message;
    }

    /**
     * send message to telegram group by bot
     *
     * @param string $message
     */
    public function sendMessageToBot(string $message)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://support.parspn.com/telegram_message/$message",
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Exception $e) {}
    }

    /**
     * @param $date 'Y-m-d'
     * @param bool $special
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|object|null
     */
    public function dailyGiftSelector($date, bool $special = false)
    {
        if ($special) {
            $dailyGift = DailyGift::where('special', 1)
                ->where('created_at', '>', strtotime(date('Y-m-d') . ' 00:00:00'))
                ->where('created_at', '<', strtotime(date('Y-m-d') . ' 23:59:59'))
                ->first();

        } else {
            $gift = Gift::where('id', '!=' , 1)->first();
            if ($gift) {
                $dailyGift = DailyGift::with(['user'])
                    ->find($gift->id);
                $gift->id = 1;
                $gift->save();

            } else {
                $users_id = DailyGift::where('special', 1)
                    ->pluck('user_id')->toArray();
                $_2nd_users_id = GiftRequest::pluck('id')->toArray();
                $users_id = array_unique(array_merge($users_id, $_2nd_users_id));

                $dailyGift = DailyGift::with(['user'])
                    ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                    ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                    ->where('special', 0)
                    ->whereNotIn('user_id', $users_id)
                    ->inRandomOrder()->first();
            }
        }
        return $dailyGift;
    }
}
