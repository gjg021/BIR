<?php

namespace App\Http\Controllers\BIR;

use App\Http\Controllers\Controller;
use App\Models\BIR\RIS;
use App\Models\BIR\RISDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class RISController extends Controller
{
    public function create(){
        return view('bir.ris.create');
    }

    public function edit($slug){
        $ris = RIS::query()->with(['details'])
            ->where('slug','=',$slug)
            ->first();
        $ris ?? abort(503,'RIS not found');
        return view('bir.ris.edit')->with([
            'ris' => $ris,
        ]);
    }


    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            $ris = RIS::query();
            return DataTables::of($ris)
                ->addColumn('action',function($data){
                    return view('bir.ris.dtActions')->with([
                        'data' => $data,
                    ]);
                })
                ->addColumn('details',function($data){
                    
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('bir.ris.index');
    }

    public function store(Request $request){

        $ris = new RIS();
        $ris->slug = Str::random();
        $ris->entity_name = $request->entity_name;
        $ris->division = $request->division;
        $ris->fund_cluster = $request->fund_cluster;
        $ris->rcc = $request->rcc;
        $ris->office = $request->office;
        $ris->ris_no = $request->ris_no;

        if(count($request->details) > 0){
            $arr = [];
            foreach ($request->details as $detail){
                $toPush = [
                    'ris_slug' => $ris->slug,
                    'slug' => Str::random(),
                    'stock_no' => $detail['stock_no'],
                    "article" => $detail['article'],
                    "article_name" => $detail['article_name'],
                    "description" => $detail['description'],
                    "uom" => $detail['uom'],
                    "qty_requested" => $detail['qty_requested'],
                    "qty_issued" => $detail['qty_issued'],
                    "is_available" => 1,
                    'remarks' => $detail['remarks'],
                ];
                array_push($arr,$toPush);
            }
        }

        if(RISDetails::insert($arr)){
            if($ris->save()){
                return $ris->only('slug');
            }
        }
        abort(503,'Error saving RIS.');
    }


    public function update(Request $request, $slug){
        $ris = RIS::query()
            ->where('slug','=',$slug)
            ->first();
        $ris ?? abort(503,'RIS not found.');

        $ris->entity_name = $request->entity_name;
        $ris->division = $request->division;
        $ris->fund_cluster = $request->fund_cluster;
        $ris->rcc = $request->rcc;
        $ris->office = $request->office;
        $ris->ris_no = $request->ris_no;

        if(count($request->details) > 0){
            $arr = [];
            foreach ($request->details as $detail){
                $toPush = [
                    'ris_slug' => $ris->slug,
                    'slug' => Str::random(),
                    'stock_no' => $detail['stock_no'],
                    "article" => $detail['article'],
                    "article_name" => $detail['article_name'],
                    "description" => $detail['description'],
                    "uom" => $detail['uom'],
                    "qty_requested" => $detail['qty_requested'],
                    "qty_issued" => $detail['qty_issued'],
                    "is_available" => 1,
                    'remarks' => $detail['remarks'],
                ];
                array_push($arr,$toPush);
            }
        }

        $ris->details()->delete();
        if(RISDetails::insert($arr)){
            if($ris->save()){
                return $ris->only('slug');
            }
        }
        abort(503,'Error saving RIS.');
    }
}