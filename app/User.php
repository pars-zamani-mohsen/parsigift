<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

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
    public static $modulename = array('en' => 'user', 'fa' => 'کاربر', 'model' => 'User');

    /**
     * module fields for select and search
     * @var string[]
     */
    public static $modulefields = array('id', 'name', 'tell', 'cart_number', 'active', 'role', 'created_at', 'updated_at', 'created_by');

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
     * Save model event log
     *
     * @return bool
     */
    public static function boot()
    {
        parent::boot();
        static::created(function ($item) { ActivityLogController::savelog('create', self::$modulename['model'], $item['id'], Auth::id(), $item); });
        static::updated(function ($item) { ActivityLogController::savelog('update', self::$modulename['model'], $item['id'], Auth::id(), $item); });
        static::deleted(function ($item) { ActivityLogController::savelog('delete', self::$modulename['model'], $item['id'], Auth::id(), $item); });
        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'tell', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param $query
     * @param $role
     */
    public function scopeRole($query, array $role)
    {
        $query->whereIn('role', $role);
    }

    /**
     * @param $user_id
     * @return null
     */
    public static function getUser($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            return $user;
        }
        return null;
    }

    /**
     * @param $url
     * @return string
     */
    public static function getSiteUrl($url)
    {
        return URL::to('/') . $url;
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

    /**
     * Check active item
     *
     * @param $query
     * @return mixed
     */
    public function scopeRD($query)
    {
        return $query->where('r_and_d_check', 1);
    }

    # Popular functions
    /**
     * fetch selected record
     *
     * @param $id
     * @return mixed
     */
    public function _find($id)
    {
        return User::find($id);
    }

    /**
     * fetch All records
     * @return mixed
     */
    public function fetchAll()
    {
        return User::orderBy('id', 'DESC')->get();
    }

    /**
     * fetch All records(paginate)
     *
     * @param $limit
     * @return mixed
     */
    public function fetchAll_paginate($limit)
    {
        return User::with(['publisher'])->orderBy('id', 'DESC')->paginate($limit);
    }

    /**
     * fetch All active records
     * @return mixed
     */
    public static function fetchAll_active()
    {
        return User::active()->orderBy('id', 'DESC')->get();
    }

    /**
     * fetch All deleted records
     *
     * @param int $limit
     * @return mixed
     */
    public static function fetch_allTrush_limited_columns(int $limit)
    {
        return User::select('id', 'name', 'created_at', 'deleted_at', 'created_by')->onlyTrashed()->paginate($limit);
    }

    # Relations

    /**
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailyQuerys()
    {
        return $this->hasMany('App\DailyQuery', 'user_id')->orderByDesc('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dailyQuery()
    {
        return $this->hasOne('App\DailyQuery', 'user_id')->where('status', 1)->orderByDesc('id');
    }
}
