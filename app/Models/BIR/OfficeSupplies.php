<?php

namespace App\Models\BIR;

use Illuminate\Database\Eloquent\Model;

class OfficeSupplies extends Model
{
    protected $table = 'bir_office_supplies';

    public $timestamps = true;

    public function stockEntryDetails(){
        return $this->hasMany(StockEntryDetails::class,'stock_no','stock_no');
    }

    public function risDetails(){
        return $this->hasMany(RISDetails::class,'stock_no','stock_no');
    }
}