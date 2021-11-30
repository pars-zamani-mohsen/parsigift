<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\ActivityLogController;

class ActivityLog extends Model
{
    use SoftDeletes;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The model information
     * @var string[]
     */
    public static $modulename = array('en' => 'activitylog', 'fa' => 'سابقه فعالیت', 'model' => 'ActivityLog');

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'collection',
    ];

    # Popular functions
    /**
     * fetch selected record
     *
     * @param $id
     * @return mixed
     */
    public function _find($id)
    {
        return ActivityLog::find($id);
    }

    /**
     * fetch All records
     * @return mixed
     */
    public function fetchAll()
    {
        return ActivityLog::all();
    }

    /**
     * fetch All records(paginate)
     *
     * @param $limit
     * @return mixed
     */
    public function fetchAll_paginate($limit)
    {
        return ActivityLog::with('user')->paginate($limit);
    }

    /**
     * fetch All active records
     * @return mixed
     */
    public static function fetchAll_active()
    {
        return ActivityLog::all();
    }

    /**
     * fetch All deleted records
     *
     * @param int $limit
     * @return mixed
     */
    public static function fetch_allTrush_limited_columns(int $limit)
    {
        return ActivityLog::select('id', 'model AS title', 'created_at', 'deleted_at', 'user_id AS created_by')->onlyTrashed()->paginate($limit);
    }

    # Relations

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
