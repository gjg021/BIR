<?php

namespace App\Models\BIR;

use Illuminate\Database\Eloquent\Model;

class StockEntryDetails extends Model
{
    protected $table = 'bir_stock_entry_details';

    public function stockEntry(){
        return $this->belongsTo(StockEntry::class,'stock_entry_slug','slug');
    }
}