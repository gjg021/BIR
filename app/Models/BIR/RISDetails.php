<?php

namespace App\Models\BIR;

use Illuminate\Database\Eloquent\Model;

class RISDetails extends Model
{
    protected $table = 'bir_ris_details';

    public function ris(){
        return $this->belongsTo(RIS::class,'ris_slug','slug');
    }
}