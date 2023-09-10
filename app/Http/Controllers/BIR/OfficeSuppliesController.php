<?php

namespace App\Http\Controllers\BIR;

use App\Models\BIR\OfficeSupplies;
use App\Models\BIR\RIS;
use App\Models\BIR\RISDetails;
use App\Models\BIR\StockEntry;
use App\Models\BIR\StockEntryDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class OfficeSuppliesController
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            $supplies = OfficeSupplies::query()
                ->with(['stockEntryDetails','risDetails'])
                ->withSum('stockEntryDetails','qty')
                ->withSum('risDetails','qty_issued');
            return DataTables::of($supplies)
                ->addColumn('action',function($data){
                    return view('bir.office_supplies.dtActions')->with([
                        'data' => $data
                    ]);
                })
                ->editColumn('stock',function ($data){
//                    return  $data;
                    return $data->stock + ($data->stock_entry_details_sum_qty ?? 0) - ($data->ris_details_sum_qty_issued ?? 0);
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('bir.office_supplies.index');
    }
    public function store(Request $request){
        $supply = new OfficeSupplies();
        $supply->slug = Str::random();
        $supply->classification = $request->classification;
        $supply->stock_no = $request->stock_no;
        $supply->article = $request->article;
        $supply->description = $request->description;
        $supply->uom = $request->uom;
        $supply->reordering_point = $request->reordering_point;
        $supply->stock = $request->stock;
        if($supply->save()){
            return $supply->only('slug');
        }
        abort(503,'Error saving supply.');
    }

    public function show($slug){
        $officeSupply = OfficeSupplies::query()
            ->with(['risDetails','stockEntryDetails'])
            ->where('slug','=',$slug)
            ->first();
        $officeSupply ?? abort(503,'Office Supply not found.');

        $stockEntry = StockEntryDetails::query()
            ->selectRaw("'stock_entry' as type,qty,date, po_no as reference")
            ->where('stock_no','=',$officeSupply->stock_no)
            ->leftJoin(
                app(StockEntry::class)->getTable(),
                app(StockEntry::class)->getTable().'.slug',
                '=',
                app(StockEntryDetails::class)->getTable().'.stock_entry_slug'
            );
        $risDetails = RISDetails::query()
            ->selectRaw("'ris' as type, qty_issued as qty, date,ris_no as reference")
            ->where('stock_no','=',$officeSupply->stock_no)
            ->leftJoin(
                app(RIS::class)->getTable(),
                app(RIS::class)->getTable().'.slug',
                '=',
                app(RISDetails::class)->getTable().'.ris_slug'
            )->union($stockEntry)
            ->orderBy('date','asc')
            ->get();

        $balanceSheet = $risDetails;

        return view('bir.office_supplies.show')->with([
            'officeSupply' => $officeSupply,
            'balanceSheet' => $balanceSheet,
        ]);
    }
}