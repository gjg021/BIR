<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JoEmployees extends Model
{

    public static function boot()
    {
        static::creating(function ($menu){
            $menu->user_created = Auth::user()->user_id;
            $menu->ip_created = request()->ip();
        });

        static::updating(function ($menu){
            $menu->user_updated = Auth::user()->user_id;
            $menu->ip_updated = request()->ip();
        });
    }

    protected $table = 'hr_jo_employees';
    protected $attributes = [
        'biometric_user_id' => 0,
    ];

    public function dtr_records(){
        return $this->hasMany('App\Models\DailyTimeRecord','biometric_user_id','biometric_user_id');
    }

    public function creator(){
        return $this->hasOne("App\Models\User","user_id","user_created");
    }

    public function updater(){
        return $this->hasOne("App\Models\User","user_id","user_updated");
    }

}