<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Gift;
use App\Message;
use App\DailyGift;
use App\DailyQuery;
use App\GiftRequest;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class GiftRequestController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validate_data = array(
            'ip' => ['required', 'ip'],
            'url' => ['required', 'string'],
            'device' => ['required', 'string'],
            'mobile' => ['required', 'string', new Mobile()],
        );
        $this->validate($request, $validate_data);

        /* check user exsit */
        $current_user = User::where('tell', Date::convertPersianNumToEnglish($request->mobile))->first();

        /* check duplicate device and ip */
        $duplicate_device = User::where('device', $request->device)->where('ip', $request->ip)->where('tell', '<>', Date::convertPersianNumToEnglish($request->mobile))->first();

        if (!$duplicate_device) {
            if ($current_user) {
                /* check user active */
                if ($current_user->active && $current_user->r_and_d_check) {
                    $date = Date('Y-m-d');
                    $dailyQuerys = DailyQuery::with(['_query'])
                        ->where('status', 0)
                        ->where('user_id', $current_user->id)
                        ->where('created_at', '>', strtotime($date . ' 00:00:00'))
                        ->where('created_at', '<', strtotime($date . ' 23:59:59'))
                        ->get();

                    /* check user has daily query */
                    if (count($dailyQuerys)) {
                        $_dailyQuery_target = false;
                        foreach ($dailyQuerys as $_dailyQuery) {
                            /* check url and get instance */
                            if (strpos(urldecode($request->url), urldecode($_dailyQuery->_query->url)) !== false) {
                                $_dailyQuery_target = $_dailyQuery;
                            }
                        }

                        /* check instance is exist */
                        if ($_dailyQuery_target) {
                            /** success **/
                            /* complate daily query */
                            $_dailyQuery_target->status = 1;
                            $_dailyQuery_target->device = $request->device;
                            $_dailyQuery_target->ip = $request->ip;
                            $_dailyQuery_target->save();

                            /* complate user field */
                            $current_user->device = $request->device;
                            $current_user->ip = $request->ip;
                            $current_user->save();

                            if (count($dailyQuerys) == 1) {
                                /* insert special gift */
                                $dailyGift = new DailyGift();
                                $dailyGift->title = 'هدیه روزانه پارسی گیفت';
                                $dailyGift->amount = 5000;
                                $dailyGift->user_id = $current_user->id;
                                $dailyGift->save();
                            }

                            $message = array(
                                'success' => true,
                                'message' => array(
                                    'text' => 'درخواست شما ثبت شد.',
                                    'code' => 200,
                                )
                            );

                        } else {
                            $message = array(
                                'success' => false,
                                'message' => array(
                                    'text' => 'شما روی لینک اشتباه در گوگل کلیک کردید، لطفا دوباره سعی کنید',
                                    'code' => 104,
                                )
                            );
                        }

                    } else {
                        $message = array(
                            'success' => false,
                            'message' => array(
                                'text' => 'هیچ کوئری فعالی برای امروز وجود ندارد، لطفا وارد پنل خود شده و کوئری ها را بررسی نمایید',
                                'code' => 103,
                            )
                        );
                    }

                } else {
                    $message = array(
                        'success' => false,
                        'message' => array(
                            'text' => 'شماره موبایل وارد شده غیرفعال است',
                            'code' => 102,
                        )
                    );
                }

            } else {
                $message = array(
                    'success' => false,
                    'message' => array(
                        'text' => 'شماره موبایل وارد شده ثبت نشده است',
                        'code' => 101,
                    )
                );
            }
        } else {
            $message = array(
                'success' => false,
                'message' => array(
                    'text' => 'این دستگاه برای شخص دیگری در حال استفاده است، لطفا با دستگاه دیگری امتحان کنید',
                    'code' => 100,
                )
            );
        }

        return response()->json($message, 201);
    }
}
