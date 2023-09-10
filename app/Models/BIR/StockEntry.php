<?php

namespace App\Models\BIR;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $table = 'bir_stock_entry';

    public function details(){
        return $this->hasMany(StockEntryDetails::class,'stock_entry_slug','slug');
    }
}