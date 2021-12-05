<?php

namespace App\Http\Controllers\API;

use App\Gift;
use App\GiftRequest;
use App\Message;
use App\Rules\Mobile;
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
            'url' => ['required', 'string'],
            'mobile' => ['required', 'string', new Mobile()],
        );
        $this->validate($request, $validate_data);

        $waiting = 5;
        $created_at = strtotime("-$waiting minutes", time());
        $checker = GiftRequest::select('id')->where('mobile', $request->mobile)->where('created_at', '>=' , $created_at)->first();

        if (!$checker) {
            $gift = Gift::active()->inRandomOrder()->first();

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
        }

        return response()->json(array('success' => false, 'errors' => array("ثبت تکراری در بازه $waiting دقیقه")), 201);
    }
}
