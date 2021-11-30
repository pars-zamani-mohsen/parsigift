<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recyclebin extends Model
{
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    public static $modulename = array('en' => 'recyclebin', 'fa' => 'سطل بازیابی', 'model' => 'Recyclebin');

    /**
     * fetch All records(paginate)
     *
     * @param $limit
     * @return mixed
     */
    public function fetchAll_paginate($limit)
    {
        return Recyclebin::onlyTrashed()->paginate($limit);
    }

    /**
     * fetch All Module Name
     * @return array
     */
    public function fetchAllModuleName()
    {
        return array(
            \App\ActivityLog::$modulename['model'] => \App\ActivityLog::$modulename['fa'],
            \App\User::$modulename['model'] => \App\User::$modulename['fa'],
            \App\Gift::$modulename['model'] => \App\Gift::$modulename['fa'],
            \App\GiftRequest::$modulename['model'] => \App\GiftRequest::$modulename['fa'],
        );
    }
}
