<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseFilterRequest extends FormRequest{



   
    public function authorize(){

        return true;
    
    }

    



    public function rules(){

        return [
            
            'q' => 'nullable|string|max:90',
            
        ];
    
    }




}
