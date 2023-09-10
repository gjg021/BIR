<?php

namespace App\Http\Controllers\BIR;

use App\Http\Controllers\Controller;
use App\Models\BIR\StockEntry;
use App\Models\BIR\StockEntryDetails;
use App\Swep\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class StockEntryController extends Controller
{

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            $stocks = StockEntry::query()
                ->with(['details']);

            return DataTables::of($stocks)
                ->addColumn('action',function($data){
                    return view('bir.stock_entry.dtActions')->with([
                        'data' => $data,
                    ]);
                })
                ->addColumn('details',function($data){

                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('bir.stock_entry.index');
    }

    public function create(){
        return view('bir.stock_entry.create');
    }

    public function edit($slug){
        $stockEntry = StockEntry::query()
            ->with(['details'])
            ->where('slug','=',$slug)
            ->first();
        $stockEntry ?? abort(503,'Stock not found');

        return view('bir.stock_entry.edit')->with([
            'stockEntry' => $stockEntry,
        ]);
    }

    public function store(Request $request){
        $stock = new StockEntry();
        $stock->slug = Str::random();
        $stock->date = $request->date;
        $stock->po_no = $request->po_no;
        $stock->supplier = $request->supplier;

        if(count($request->details) > 0){
            $arr = [];
            foreach ($request->details as $detail){
                $toPush = [
                    'stock_entry_slug' => $stock->slug,
                    'slug' => Str::random(),
                    'stock_no' => $detail['stock_no'],
                    "article" => $detail['article'],
                    "article_name" => $detail['article_name'],
                    "description" => $detail['description'],
                    "uom" => $detail['uom'],
                    "qty" => $detail['qty'],
                    "unit_cost" => Helper::sanitizeAutonum($detail['unit_cost']),
                    "amount" => ($detail['qty'] ?? 0) * (Helper::sanitizeAutonum($detail['unit_cost']) ?? 0),
                ];
                array_push($arr,$toPush);
            }
        }
        if(StockEntryDetails::insert($arr)){
         if($stock->save()){
             return $stock->only('slug');
         }
        }
        abort(503,'a');

    }

    public function update(Request $request, $slug){
        $stock = StockEntry::query()
            ->with(['details'])
            ->where('slug','=',$slug)
            ->first();
        $stock ?? abort(503,'Stock not found');

        $stock->date = $request->date;
        $stock->po_no = $request->po_no;
        $stock->supplier = $request->supplier;

        if(count($request->details) > 0){
            $arr = [];
            foreach ($request->details as $detail){
                $toPush = [
                    'stock_entry_slug' => $stock->slug,
                    'slug' => Str::random(),
                    'stock_no' => $detail['stock_no'],
                    "article" => $detail['article'],
                    "article_name" => $detail['article_name'],
                    "description" => $detail['description'],
                    "uom" => $detail['uom'],
                    "qty" => $detail['qty'],
                    "unit_cost" => Helper::sanitizeAutonum($detail['unit_cost']),
                    "amount" => ($detail['qty'] ?? 0) * (Helper::sanitizeAutonum($detail['unit_cost']) ?? 0),
                ];
                array_push($arr,$toPush);
            }
        }
        $stock->details()->delete();
        if(StockEntryDetails::insert($arr)){
            if($stock->save()){
                return $stock->only('slug');
            }
        }
        abort(503,'a');
    }
}