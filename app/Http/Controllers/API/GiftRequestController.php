<?php

namespace App\Http\Controllers\API;

use App\DailyGift;
use App\DailyQuery;
use App\Gift;
use App\Message;
use App\GiftRequest;
use App\Rules\Mobile;
use App\User;
use Illuminate\Http\Request;
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

        $current_user = User::where('tell', $request->mobile)->first();
        /* check user exsit */
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
                        if (strpos($request->url, $_dailyQuery->_query->url) !== false){
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
                            /* insert gift */
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
                                'text' => 'آدرس ارسال شده معتبر نیست',
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

        return response()->json($message, 201);
    }

    public function old_store(Request $request)
    {
        $validate_data = array(
            'url' => ['required', 'string'],
            'mobile' => ['required', 'string', new Mobile()],
        );
        $this->validate($request, $validate_data);

        $waiting = 3;
        $created_at = strtotime("-$waiting minutes", time());
        $checker = GiftRequest::select('id')->where('mobile', $request->mobile)->where('created_at', '>=', $created_at)->first();

        if (!$checker) {
            $gift = Gift::active()->inRandomOrder()->first();

            if ($gift) {
                $instance = new GiftRequest();
                $instance->gift_id = $gift->id;
                $instance->url = $request->url;
                $instance->mobile = $request->mobile;
                $result = $instance->save();

                if ($result) {
                    /* deactive gift */
                    $gift->active = 0;
                    $gift->save();

                    /* initialize message */
                    $message = "سلام همکار عزیز" . "\n";
                    $message .= "هدیه شما: ";

                    if ($gift->qty) $message .= $gift->qty . " ";
                    $message .= $gift->title;
                    if ($gift->amount) $message .= " به ارزش " . $gift->amount . " تومان ";

                    $message .= "\n" . "با سپاس از همراهی شما";

                    /* send message */
                    Message::send_simple_sms(Message::getSmsSenderNumber(), [$request->mobile], $message);
                    return response()->json(array('success' => true), 201);
                }
            } else {
                $message = "جوایز به اتمام رسید";
            }
        } else {
            $message = "ثبت تکراری در بازه $waiting دقیقه";
        }

        return response()->json(array('success' => false, 'errors' => array($message ?? 'خطا در ثبت اطلاعات')), 201);
    }
}
