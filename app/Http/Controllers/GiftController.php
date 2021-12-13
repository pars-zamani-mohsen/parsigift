<?php

namespace App\Http\Controllers;

use App\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class GiftController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new Gift();
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validate_data = array(
            'title' => 'required|string',
        );
        $this->validate($request, $validate_data);

        $this->instance->title = $request->title;
        $this->instance->amount = $request->amount ?? 0;
        $this->instance->qty = $request->qty ?? 0;
        $this->instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
        $this->instance->created_by = (Auth::user()) ? Auth::id() : 1;
        $result = $this->instance->save();

        if ($result) {
            return $this->function_response(201);
        }

        return $this->function_response(204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $validate_data = array(
            'title' => ['required', 'string']
        );
        $this->validate($request, $validate_data);

        $instance = $this->instance->_find($id);
        if ($instance) {
            /* Edit instance */
            $instance->title = $request['title'];
            $instance->amount = $request['amount'] ?? 0;
            $instance->qty = $request['qty'] ?? 0;
            $instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
            $result = $instance->save();

            if ($result) {
                return $this->function_response(201);
            }

            return $this->function_response(204);
        }

        return $this->function_response(404);
    }

    public function autoInsertGift(Request $request)
    {
//        $gift = array('دریافت وام تا سقف ۱۰ میلیون تومان','پاداش ۵۰۰ هزارتومانی','پاداش یک میلیون تومانی','یک وعده شام دو نفره در رستوران دلخواه تا سقف ۱ میلیون تومان');
//        $gift = array('کارت هدیه پاداش');
//        $gift = array('متاسفانه بخت با شما یار نبود');
//        $qty = 300;
//        foreach ($gift as $item) {
//            for ($i = 0; $i < $qty; $i++) {
//                $this->instance = new Gift();
//                $this->instance->title = $item;
//                $this->instance->amount = 1000;
//                $this->instance->qty = 1;
//                $this->instance->active = 1;
//                $this->instance->created_by = 1;
//                $this->instance->save();
//            }
//        }
        dd('done!', $gift, $request);
    }
}
