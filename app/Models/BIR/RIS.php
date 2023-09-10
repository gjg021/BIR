<?php

namespace App\Models\BIR;

use Illuminate\Database\Eloquent\Model;

class RIS extends Model
{
    protected $table = 'bir_ris';

    public function details(){
        return $this->hasMany(RISDetails::class,'ris_slug','slug');
    }
}