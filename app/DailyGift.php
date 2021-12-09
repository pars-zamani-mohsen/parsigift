<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyGift extends Model
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
    public static $modulename = array('en' => 'dailyGift', 'fa' => 'پورسانت ها و جوایز من', 'model' => 'DailyGift');

    /**
     * module fields for select and search
     * @var string[]
     */
    public static $modulefields = array('id', 'title', 'amount', 'user_id', 'created_at', 'updated_at');

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
     * The attributes for commentable record.
     *
     * @value false, true
     * @var array
     */
    public static $commentable = false;

    /**
     * Save model event log
     *
     * @return bool
     */
//    public static function boot()
//    {
//        parent::boot();
//        static::created(function ($item) { ActivityLogController::savelog('create', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
//        static::updated(function ($item) { ActivityLogController::savelog('update', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
//        static::deleted(function ($item) { ActivityLogController::savelog('delete', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
//        return false;
//    }

    /**
     * Check active item
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }


    # Popular functions

    /**
     * fetch selected record
     *  Require *
     * @param $id
     * @return mixed
     */
    public function _find($id)
    {
        return DailyGift::find($id);
    }

    /**
     * fetch All records(paginate)
     * Require *
     * @param $limit
     * @return mixed
     */
    public function fetchAll_paginate($limit)
    {
        return DailyGift::select(self::$modulefields)->with(['user'])->orderBy('id', 'DESC')->paginate($limit);
    }

    /**
     * fetch All deleted records
     * Require * (for recycle bin)
     *
     * @param int $limit
     * @return mixed
     */
    public static function fetch_allTrush_limited_columns(int $limit)
    {
        return DailyGift::select('id', 'title', 'amount', 'user_id', 'created_at', 'created_by')->onlyTrashed()->paginate($limit);
    }

    /**
     * fetch All active records
     * @return mixed
     */
    public static function fetchAll_active_limited_columns()
    {
        return DailyGift::select(self::$modulefields)->active()->get();
    }

    /**
     * fetch All records(paginate) with user_id condition
     *
     * @param int $limit
     * @param int $user_id
     * @return mixed
     */
    public function fetchAll_paginate_with_userid(int $limit, int $user_id)
    {
        return DailyGift::select(self::$modulefields)->with(['user'])->orderBy('id', 'DESC')->paginate($limit);
    }

    /**
     * fetch All records
     * @return mixed
     */
    public function fetchAll()
    {
        return DailyGift::all();
    }

    # Relations

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
