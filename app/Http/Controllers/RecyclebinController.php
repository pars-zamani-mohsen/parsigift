<?php

namespace App\Http\Controllers;

use App\Recyclebin;

use Illuminate\Http\Request;

class RecyclebinController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = new Recyclebin();
        $this->modulename = $this->instance::$modulename;
        $this->parent = array('path' => HomeController::fetch_manager_pre_path(), 'url' => HomeController::fetch_manager_pre_url());
    }

    /**
     * Display a listing of the resource.
     *
     * Return params can have: onlylist, is_related_list, import to add or remove buttons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
            'modulename' => $this->modulename,
            'title' => $this->modulename['fa'],
            'modulenamelist' => $this->instance->fetchAllModuleName(),
            'all' => array(),
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * Return params can have: onlylist, is_related_list, import to add or remove buttons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list($id)
    {
        $model = '\\App\\' . $id;
        return view($this->parent['path'] . '.' . $this->modulename['en'] . '.list', array(
            'modulename' => $this->modulename,
            'title' => $this->modulename['fa'],
            'modulenamelist' => $this->instance->fetchAllModuleName(),
            's_modulename' => $id,
            'all' => $model::fetch_allTrush_limited_columns(20)
        ));
    }


    /**
     * Restore the specified resource from storage.
     *
     * @param $model
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($model, $id)
    {
        $c_model = '\\App\\' . $model;
        $instance = $c_model::withTrashed()->find($id);
        if ($instance) {
            $instance->restore();
            return $this->function_response(202);
        }

        return $this->function_response(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $model
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($model, $id)
    {
        $c_model = '\\App\\' . $model;

        $instance = $c_model::withTrashed()->find($id);
        if ($instance) {
            $instance->forceDelete();
            return $this->function_response(202);
        }

        return $this->function_response(404);
    }
}
