<?php

namespace App;

use App\Http\Controllers\ActivityLogController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GiftRequest extends Model
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
    public static $modulename = array('en' => 'gift_request', 'fa' => 'درخواست هدیه', 'model' => 'GiftRequest');

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
    public static function boot()
    {
        parent::boot();
        static::created(function ($item) { ActivityLogController::savelog('create', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
        static::updated(function ($item) { ActivityLogController::savelog('update', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
        static::deleted(function ($item) { ActivityLogController::savelog('delete', self::$modulename['model'], $item['id'], Auth::id() ?? 0, $item); });
        return false;
    }

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
        return GiftRequest::find($id);
    }

    /**
     * fetch All records(paginate)
     * Require *
     * @param $limit
     * @return mixed
     */
    public function fetchAll_paginate($limit)
    {
        return GiftRequest::select('id', 'gift_id', 'url', 'mobile', 'created_at')->with(['gift'])->orderBy('id', 'DESC')->paginate($limit);
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
        return GiftRequest::select('id', 'gift_id', 'url', 'mobile', 'created_at')->onlyTrashed()->paginate($limit);
    }

    /**
     * fetch All active records
     * @return mixed
     */
    public static function fetchAll_active_limited_columns()
    {
        return GiftRequest::select('id', 'gift_id', 'url', 'mobile', 'created_at')->active()->get();
    }

    /**
     * fetch All records
     * @return mixed
     */
    public function fetchAll()
    {
        return GiftRequest::all();
    }

    # Relations

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gift()
    {
        return $this->belongsTo('App\Gift');
    }
}
