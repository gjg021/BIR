<?php


namespace App\Http\Controllers;


use App\Models\Applicant;
use App\Models\ApplicantPositionApplied;
use App\Models\Budget\ChartOfAccounts;
use App\Models\Course;
use App\Models\Document;
use App\Models\Employee;
use App\Models\HRPayPlanitilla;
use App\Models\PPU\Pap;
use App\Models\SSL;
use App\Swep\Helpers\Helper;
use App\Swep\Services\Budget\ORSService;
use App\Swep\Services\Budget\PapService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AjaxController extends Controller
{

    protected $papService;
    protected $orsService;
    public function __construct(PapService $papService, ORSService $orsService)
    {
        $this->papService = $papService;
        $this->orsService = $orsService;
    }

    public function get($for, ORSService $ORSService, Request $r){

        if($for == 'compute_monthly_salary'){
            return $this->compute_monthly_salary();
        }
        if($for == 'educational_background'){
            return view('ajax.employee.add_school');
        }

        if($for == 'eligibility'){
            return view('ajax.employee.add_eligibility');
        }

        if($for == 'work_experience'){
            $rand = Str::random(16);
            return [
                'view' => view('ajax.employee.add_work_experience')->with([
                                'rand' => $rand,
                            ])->render(),
                'rand' => $rand,
            ];
        }

        if($for == 'close_bulletin'){
            return $this->close_bulletin();
        }

        if($for == 'document_person_to'){
            return $this->document_person_to();
        }
        if($for == 'document_person_from'){
            return $this->document_person_from();
        }
        if($for == 'dv_add_item'){
            return $this->dv_add_item();
        }

        if($for == 'position_applied'){
            return $this->position_applied();
        }

        if($for == 'applicant_courses'){
            return $this->applicant_courses();
        }
        if($for == 'search_active_employees'){
            return $this->search_active_employees();
        }

        if($for == 'applicant_filter_position'){
            return $this->applicant_filter_position();
        }

        if($for == 'applicant_filter_item_no'){
            return $this->applicant_filter_item_no();
        }

        if($for == 'add_row'){
            return view('ajax.dynamic.'.\Illuminate\Support\Facades\Request::get('view'));
        }

        if($for == 'account'){
            $arr = [];
            $like = '%'.request('q').'%';
            $accounts = ChartOfAccounts::query()
                ->select('account_code' ,'account_title')
                ->where('account_code','like',$like)
                ->orWhere('account_title','like',$like)
                ->orderBy('account_title','asc');

            if(request()->has('page')){
                $accounts = $accounts->offset((request('page')-1)*10);
            }
            $accounts = $accounts->limit(10)
                ->get();

            if(!empty($accounts)){
                foreach ($accounts as $account){
                    array_push($arr,[
                        'id' => $account->account_code,
                        'text' => $account->account_title.' - '.$account->account_code,
                        'populate' => [
                            'account_title' => $account->account_title,
                            'account_code' => $account->account_code,
                        ]
                    ]);
                }
            }
            return Helper::wrapForSelect2($arr);
        }

        if($for == 'ors_certified_by'){
            $data = null;
            $employees = Employee::query()
                ->select('lastname','firstname','middlename','position')
                ->where('is_active','ACTIVE')
                ->where(function($q){
                    $q->where('locations','=','VISAYAS')
                        ->orWhere('locations','=','LUZON/MINDANAO');
                })
                ->orderBy('salary_grade','desc')
                ->orderBy('firstname','asc');


            if($r->has('q') && $r->q != ''){
                $employees = $employees->where(function ($q) use ($r){
                    $q->where('lastname','like','%'.$r->q.'%')
                        ->orWhere('firstname','like','%'.$r->q.'%')
                        ->orWhere('middlename','like','%'.$r->q.'%');
                });
            }

            if($r->has('page')){
                $employees = $employees->offset((($r->page) - 1) * 10);
            }


            $employees = $employees->limit(10)->get();

            if($employees->count() > 0){
                $data = $employees->map(function ($data){
                    return [
                        'id' => $data->firstname.' '.Helper::middleInitial($data->middlename).' '.$data->lastname,
                        'text' => $data->firstname.' '.Helper::middleInitial($data->middlename).' '.$data->lastname,
                        'position' => $data->position,
                    ];
                });
                return Helper::wrapForSelect2($data->toArray());
            }
            return false;
        }

        if($for == 'pap'){
            $arr = [];
            $like = '%'.request('q').'%';
            $paps = Pap::query()
                ->select('pap_code' ,'pap_title', 'slug');
            if(request('respCode') != ''){
                $paps = $paps->where('resp_center','=',request('respCode'));
            }

            $paps = $paps->where(function ($q) use ($like){
                    $q->where('pap_code','like',$like)
                    ->orWhere('pap_title','like',$like);
                })
                ->orderBy('pap_code','asc')
                ->limit(10);
            if(request()->has('page')){
                $paps = $paps->offset((request('page')-1)*10);
            }
            $paps = $paps->get();

            if(!empty($paps)){
                foreach ($paps as $pap){
                    array_push($arr,[
                        'id' => $pap->pap_code,
                        'text' => $pap->pap_code.' | '.$pap->pap_title,
                        'slug' => $pap->slug,
                        'populate' => [
                            'pap_code' => $pap->pap_code,
                            'pap_title' => $pap->pap_title,
                        ]
                    ]);
                }
            }
            if($paps->count() >= 10){
                return Helper::wrapForSelect2($arr);
            }else{
                return Helper::wrapForSelect2($arr,false);
            }
        }

        if($for == 'ors_payees'){
            return $this->orsService->__typeAhead_payee($r);
        }

        //check for pap balances;

        if($for == 'ors_pap_balances'){
            $request = \Illuminate\Http\Request::capture();
            return $this->papService->getBalancesBySlug($request->slug);
        }

        if($for == 'orsAccountEntry'){
            if($r->type == 'DV'){
                $r->type = 'ORS';
            }else{
                $r->type = 'DV';
            }

            return view('ajax.dynamic.ors_account_entry')->with([
                'data' => $r,
            ]);
        }

        if($for == 'nextOrsNo'){
            $request = \Illuminate\Http\Request::capture();
            return $ORSService->newOrsNumber($request->fund);
        }
    }

    private function applicant_filter_item_no(){
        $arr['results'] = [];
        array_push($arr['results'],['id'=>'','text' => "Don't Filter"]);
        $ps = HRPayPlanitilla::query()->select('item_no','position')
            ->where('position','like','%'.Request::get('q').'%')
            ->orWhere('item_no','like','%'.Request::get('q').'%')
            ->groupBy('item_no')
            ->orderBy('item_no','asc')
            ->limit(20)
            ->get();
        if(!empty($ps)){
            foreach ($ps as $p){
                array_push($arr['results'],[
                    'id' => $p->item_no,
                    'text' => $p->item_no.' - '.$p->position,
                ]);
            }
        }
        return $arr;
    }
    private function applicant_filter_position(){
        $arr['results'] = [];
        array_push($arr['results'],['id'=>'','text' => "Don't Filter"]);
        $ps = ApplicantPositionApplied::query()->select('position_applied')
            ->where('position_applied','like','%'.Request::get('q').'%')
            ->groupBy('position_applied')
            ->orderBy('position_applied','asc')
            ->limit(20)
            ->get();
        if(!empty($ps)){
            foreach ($ps as $p){
                array_push($arr['results'],[
                    'id' => $p->position_applied,
                    'text' => $p->position_applied,
                ]);
            }
        }
        return $arr;
    }
    private function compute_monthly_salary(){
        $latest = SSL::query()->orderBy('date_implemented','desc')->first();
        $latest_date_implemented = $latest->date_implemented;
        $ssl = SSL::query()->where('salary_grade','=',Request::get('sg'))
            ->where('date_implemented','=',$latest_date_implemented)
            ->first();
        $si = 'step'.Request::get('si');

        if(!empty($ssl->$si)){
            return number_format($ssl->$si,2);
        }
        else{
            return 'N/A';
        }
    }

    private function close_bulletin(){
        $last_slug = request('last_slug');
        Session::put('last_slug',$last_slug);

        return Session::get('last_slug');
    }

    private function document_person_to(){
        $arr['results'] = [];
        $docs = Document::query()->select('person_to')->where('person_to','like','%'.\Illuminate\Support\Facades\Request::get("q").'%')->groupBy('person_to')->limit(30)->get();
        array_push($arr['results'],['id'=>'','text' => "Don't Filter"]);
        if(!empty($docs)){

            foreach ($docs as $doc){
                array_push($arr['results'],['id'=>$doc->person_to,'text' => $doc->person_to]);
            }
        }
        return $arr;
    }

    private function document_person_from(){
        $arr['results'] = [];
        $docs = Document::query()->select('person_from')->where('person_from','like','%'.\Illuminate\Support\Facades\Request::get("q").'%')->groupBy('person_from')->limit(30)->get();
        array_push($arr['results'],['id'=>'','text' => "Don't Filter"]);
        if(!empty($docs)){
            foreach ($docs as $doc){
                array_push($arr['results'],['id'=>$doc->person_from,'text' => $doc->person_from]);
            }
        }
        return $arr;
    }

    private function dv_add_item(){
        $rcs = \App\Models\RC::query()->get();
        $rand = \Illuminate\Support\Str::random(5);
        return [
            'view' => view('ajax.disbursement_voucher.add_item')->with([
                'rcs'=>$rcs,
                'rand' => $rand,
            ])->render(),
            'rand' => $rand,
        ];
    }

    private function position_applied(){
        $arr = [];
        $pps = HRPayPlanitilla::query()->select('item_no','position')->get();
        foreach ($pps as $pp){
            array_push($arr,'ITEM '.$pp->item_no.' - '.$pp->position);
        }
        return $arr;
    }

    private function applicant_courses(){
        $arr['results'] = [];
        $courses = Course::query()->where('acronym','like','%'.\Illuminate\Support\Facades\Request::get("q").'%')
            ->orWhere('name','like','%'.\Illuminate\Support\Facades\Request::get("q").'%')
            ->groupBy('name')->limit(30)->get();
        if(\Illuminate\Support\Facades\Request::get('default') == 'Select'){
            array_push($arr['results'],['id'=>'','text' => "Select"]);
        }else{
            array_push($arr['results'],['id'=>'','text' => "Don't Filter"]);
        }
        if(!empty($courses)){
            foreach ($courses as $course){
                array_push($arr['results'],['id'=>$course->name,'text' => $course->name]);
            }
        }
        return $arr;
    }

    private function search_active_employees(){
        if(\Illuminate\Support\Facades\Request::get('afterTypeahead') == true){
            $emp = Employee::query()
                ->select('lastname','firstname','middlename','sex','date_of_birth','civil_status','cell_no')
                ->where('slug','=',\Illuminate\Support\Facades\Request::get('id'))->first();

            return [
                'lastname' => $emp->lastname,
                'firstname' => $emp->firstname,
                'middlename' => $emp->middlename,
                'sex' => $emp->sex,
                'date_of_birth' => Carbon::parse($emp->date_of_birth)->format('Y-m-d'),
                'civil_status' => $emp->civil_status,
                'cell_no' => $emp->cell_no,
                'civil_status' => $emp->civil_status,
            ];
        }
        $arr = [];
        $find = \Illuminate\Support\Facades\Request::get('query');

        $emps = Employee::query()
            ->where(function ($query) use($find){
                $query->where('lastname','like','%'.$find.'%')
                    ->orWhere('firstname','like','%'.$find.'%')
                    ->orWhere('middlename','like','%'.$find.'%');
            })
            ->limit(10)
            ->get();

        if(!empty($emps)){
            foreach ($emps as $emp){
                array_push($arr,[
                    'id' => $emp->slug,
                    'name' => $emp->lastname.', '.$emp->firstname.' '.$emp->middlename,
                    'sex' => $emp->sex,
                ]);
            }
        }

        return $arr;
    }
}