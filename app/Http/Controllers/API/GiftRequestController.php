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
        $current_user = User::select('id', 'active', 'r_and_d_check', 'device', 'ip')->where('tell', Date::convertPersianNumToEnglish($request->mobile))->first();

        if ($current_user) {

            /* check duplicate device and ip */
            $duplicate_device = User::select('id')->where('device', $request->device)->where('ip', $request->ip)->where('id', '!=', $current_user->id)->first();
            if (!$duplicate_device) {

                /* check user active */
                if ($current_user->active && $current_user->r_and_d_check) {

                    $waiting = 3;
                    $created_at = strtotime("-$waiting minutes", time());
                    $checker = DailyQuery::select('id')
                        ->where('user_id', $current_user->id)
                        ->where('updated_at', '>' , $created_at)
                        ->first();
                    if (!$checker) {

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
                                    $dailyGift->amount = DailyGift::$dailyGiftAmount;
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
                                file_put_contents('pars_link_not_exist.json',
                                    json_encode(
                                        [
                                            'message' => $message,
                                            'request' => $request->all(),
                                            'current_user' => $current_user
                                        ]
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
                            file_put_contents('pars_check_daily_query.json',
                                json_encode(
                                    [
                                        'message' => $message,
                                        'request' => $request->all(),
                                        'current_user' => $current_user
                                    ]
                                )
                            );
                        }

                    }  else {
                        $message = array(
                            'success' => false,
                            'message' => array(
                                'text' => 'شما هر سه دقیقه یکبار مجاز به ثبت اطلاعات هستید',
                                'code' => 105,
                            )
                        );
                        file_put_contents('pars_timer.json',
                            json_encode(
                                [
                                    'message' => $message,
                                    'request' => $request->all(),
                                    'current_user' => $current_user,
                                    'checker' => $checker,
                                ]
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
                    file_put_contents('pars_check_user_active.json',
                        json_encode(
                            [
                                'message' => $message,
                                'request' => $request->all(),
                                'current_user' => $current_user
                            ]
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
                file_put_contents('pars_device.json',
                    json_encode(
                        [
                            'message' => $message,
                            'request' => $request->all(),
                            'current_user' => $current_user,
                            'duplicate_device' => $duplicate_device
                        ]
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
            file_put_contents('pars_check_user.json',
                json_encode(
                    [
                        'message' => $message,
                        'request' => $request->all(),
                        'current_user' => $current_user
                    ]
                )
            );
        }

        return response()->json($message, 201);
    }
}
