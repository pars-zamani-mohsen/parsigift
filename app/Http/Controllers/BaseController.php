<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

### version: 1.0.1
class BaseController extends Controller
{
    protected $parent;
    protected $instance;
    protected $modulename;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = null;
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Display a listing of the resource.
     *
     * Return params can have: "onlylist", "is_related_list", "search", "import", "export", "shortcode", "navigation" to add or remove buttons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
                'modulename' => $this->modulename,
                'title' => ' فهرست ' . $this->modulename['fa'],
                'all' => $this->instance->fetchAll_paginate(20),
                'search' => true,
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        try {
            return view($this->parent['path'] . '.' . $this->modulename['en'] . '.edit', array(
                'modulename' => $this->modulename,
                'title' => 'ایجاد ' . $this->modulename['fa'],
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
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
            'title' => ['required', 'string'],
        );
        $this->validate($request, $validate_data);

        try {
            $this->instance->title = $request->title;
            $this->instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
            $this->instance->created_by = (Auth::user()) ? Auth::id() : 1;
            $result = $this->instance->save();

            if ($result) {
                return $this->function_response(201);
            }

            return $this->function_response(204);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $instance = $this->instance->_find($id);
            if ($instance) {
                return view($this->parent['path'] . '.' . $this->modulename['en'] . '.edit', array(
                    'modulename' => $this->modulename,
                    'title' => $this->modulename['fa'] . ' #' . $instance->id,
                    'This' => $instance,
                ));
            }

            return $this->function_response(404);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $instance = $this->instance->_find($id);
            if ($instance) {
                return view($this->parent['path'] . '.' . $this->modulename['en'] . '.edit', array(
                    'modulename' => $this->modulename,
                    'title' => $this->modulename['fa'] . ' #' . $instance->id,
                    'This' => $instance,
                ));
            }

            return $this->function_response(404);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }

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
            'title' => ['required', 'string'],
        );
        $this->validate($request, $validate_data);

        try {
            $instance = $this->instance->_find($id);
            if ($instance) {
                /* Edit instance */
                $instance->title = $request->title;
                $instance->active = (isset($request->active) && $request->active == 'on') ? 1 : 0;
                $result = $instance->save();

                if ($result) {
                    return $this->function_response(201);
                }

                return $this->function_response(204);
            }

            return $this->function_response(404);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $instance = $this->instance->_find($id);
            if ($instance) {
                $instance->delete();
            }
            return $this->function_response(202);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Active or deactive the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activation($id)
    {
        try {
            $instance = $this->instance->_find($id);
            if ($instance) {
                $instance->active = ($instance->active) ? 0 : 1;
                $instance->save();

                return $this->function_response(205);
            }

            return $this->function_response(404);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Search
     * @param Request $request Id, title, date and ...
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $query = $this->instance::query();
            $fields = $this->instance::$modulefields;
            $parameter = Date::convertPersianNumToEnglish($request->field);

            if (isset($parameter) && $parameter) {
                    # date
                if (Date::dateValidate($parameter)) {
                    $fromdate = Date::shamsiDateTimeToTimestamp($parameter . ' 00:00:00');
                    $todate = Date::shamsiDateTimeToTimestamp($parameter . ' 23:59:59');
                    $query = $query->whereBetween('created_at', [$fromdate, $todate]);
                    $query = $query->orWhereBetween('updated_at', [$fromdate, $todate]);

                    # related id
                } elseif(substr( $parameter, 0, 1 ) === "#") {
                    $parameter = str_replace('#', '', $parameter);
                    foreach ($fields as $field) {
                        if (strpos($field, '_id') === false) continue;
                        $query = $query->orwhere($field, $parameter);
                    }

                    # papular
                } else {
                    foreach ($fields as $field) {
                        if (in_array($field, ['created_at', 'updated_at', 'active'])) continue;
                        $query = $query->orwhere($field, 'like',  '%' . $parameter .'%');
                    }
                }

                /* check status */
                if (isset($request->status) && $request->status == "active") $query = $query->active();

                /* return result to view */
                return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
                    'modulename' => $this->modulename,
                    'title' => ' فهرست ' . $this->modulename['fa'],
                    'all' => $query->orderBy('id', 'DESC')->paginate(999),
                    'search' => true,
                ));
            }

            return $this->function_response(400);

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Upload file
     *
     * @param $request
     * @param $modulename
     * @return string|null
     */
    public function uploadfile($request, $modulename, $requestFileName = 'file')
    {
        try {
            $filename = "images/no-picture.png";
            if ($request->hasFile($requestFileName)) {
                $inputFile = $request->file($requestFileName);
                $name = time() . '-' . $modulename . '-' . $inputFile->getClientOriginalName();
                $folder = config('app.host_public_path') . '/files/' . $modulename;
                $inputFile->move($folder, $name);
                $filename = ('/files/' . $modulename . '/' . $name);
            }

            return $filename;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Set response and redirect
     *
     * @param int $code  201: Created and Update, 202: Deleted, 204: Error in submit, 204: Change status, 400 Bad Request, 404: Not found
     * @param string|null $message
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function function_response(int $code, string $message = null)
    {
        try {
            switch ($code) {
                case 201 :
                    Session::flash('message', $message ?? 'درخواست شما با موفقیت ثبت شد.');
                    return redirect($this->parent['url'] . '/' . $this->modulename['en']);

                case 0201 :
                    Session::flash('message', $message ?? 'درخواست شما با موفقیت ثبت شد.');
                    return redirect()->back();

                case 202 :
                    Session::flash('message', $message ?? 'رکورد مورد نظر حذف شد.');
                    return redirect()->back();

                case 204 :
                    return redirect()->back()->withErrors([$message ?? 'خطا در ثبت اطلاعات!!!']);

                case 205 :
                    Session::flash('message', $message ?? 'وضعیت رکورد مورد نظر تغییر کرد.');
                    return redirect()->back();

                case 400 :
                    Session::flash('alert', $message ?? 'درخواست نامعتبر!');
                    return redirect()->back();

                case 404 :
                    Session::flash('alert', $message ?? 'رکورد مورد نظر یافت نشد!');
                    return redirect()->back();
            }

            return null;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Fetch history for record
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHistory($id)
    {
        try {
            $activityLog = ActivityLog::with(['user'])->where('model', $this->modulename['model'])->where('subject_id', $id)->orderBy('id')->get();
            $_history = array();
            foreach ($activityLog as $key => $item) {
                if ($item['log_type'] == 'update') {
                    if ($key == 0) continue;
                    $_diff = array();
                    $_arr0 = $item['content'];
                    $_arr1 = $activityLog[$key-1]['content'];

                    try {
                        $_diff = array_keys($_arr0->diffAssoc($_arr1)->toArray());
                    } catch (\Exception $ex) { }

                    unset($_diff['updated_at']);

                    foreach ($_diff as $_diff_key => $_diff_item) {
                        if ($activityLog[$key-1]['log_type'] == 'create' && $_diff_item == 'deleted_at') {
                            unset($_diff[$_diff_key]);
                            continue;
                        }

                        if (in_array($_diff_item, array('id', 'updated_at'))) {
                            unset($_diff[$_diff_key]);
                            continue;
                        }
                        $_diff[$_diff_key] = __('fields.' . $_diff_item);
                    }

                    $_history[] = array(
                        'id' => $id,
                        'message' => 'ویرایش شد.',
                        'data' => $_diff,
                        'datetime' => Date::timestampToShamsiDatetime($item['created_at']),
                        'user' => (isset($item['user']['name']) && $item['user']['name']) ? $item['user']['name'] : null,
                    );

                } elseif ($item['log_type'] == 'create') {
                    $_history[] = array(
                        'id' => $id,
                        'message' => 'ایجاد شد.',
                        'data' => '',
                        'datetime' => Date::timestampToShamsiDatetime($item['created_at']),
                        'user' => (isset($item['user']['name']) && $item['user']['name']) ? $item['user']['name'] : null,
                    );

                } elseif ($item['log_type'] == 'delete') {
                    $_history[] = array(
                        'id' => $id,
                        'message' => 'حذف شد.',
                        'data' => '',
                        'datetime' => Date::timestampToShamsiDatetime($item['created_at']),
                        'user' => (isset($item['user']['name']) && $item['user']['name']) ? $item['user']['name'] : null,
                    );
                }
            }

            return view($this->parent['path'] . '.base-forms.history', array(
                'modulename' => $this->modulename,
                'title' => ' تاریخچه ' . $this->modulename['fa'] . ' #' . $id,
                'is_related_list' => true,
                'all' => $_history,
            ));

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }
}
