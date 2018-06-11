<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducationalBackground extends Model{


	protected $table = 'employee_educational_background';

    protected $dates = ['date_from', 'date_to'];

    public $timestamps = false;



    protected $attributes = [
        
        'employee_no' => '',
        'level' => '',
        'school_name' => '',
        'course' => '',
        'date_from' => null,
        'date_to' => null,
        'units' => null,
        'graduate_year' => 0,
        'scholarship' => '',

    ];





    // RELATIONSHIPS
    public function employee() {
    	return $this->belongsTo('App\Models\Employee','employee_no','employee_no');
    }
    




    // SCOPES
    public function scopePopulate($query){

        return $query->orderBy('graduate_year', 'desc')->get();

    }


    
    
}
