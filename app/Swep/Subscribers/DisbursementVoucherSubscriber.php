<?php 

namespace App\Swep\Subscribers;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Swep\Helpers\CacheHelper;
use App\Models\Signatory;
use App\Models\DisbursementVoucher;
use Illuminate\Cache\Repository as Cache;


class DisbursementVoucherSubscriber{


	protected $disbursement_vouchers;
	protected $signatory;
	protected $carbon;
    protected $cache;
	protected $auth;
    protected $str;
    



	public function __construct(DisbursementVoucher $disbursement_voucher, Signatory $signatory, Carbon $carbon, Cache $cache, Str $str){

		$this->disbursement_voucher = $disbursement_voucher;
		$this->signatory = $signatory;
		$this->carbon = $carbon;
        $this->cache = $cache;
		$this->str = $str;
        $this->auth = auth();


	}




	public function subscribe($events){

		$events->listen('dv.create', 'App\Swep\Subscribers\DisbursementVoucherSubscriber@onCreate');
        $events->listen('dv.update', 'App\Swep\Subscribers\DisbursementVoucherSubscriber@onUpdate');
        $events->listen('dv.destroy', 'App\Swep\Subscribers\DisbursementVoucherSubscriber@onDestroy');
        $events->listen('dv.set_no', 'App\Swep\Subscribers\DisbursementVoucherSubscriber@onSetNo');
        $events->listen('dv.confirm_check', 'App\Swep\Subscribers\DisbursementVoucherSubscriber@onConfirmCheck');

	}




	public function onCreate($disbursement_voucher, $request){

		$this->createDefaults($disbursement_voucher);

        $disbursement_voucher->payee = strtoupper($request->payee);
        $disbursement_voucher->address = strtoupper($request->address);
        $disbursement_voucher->amount = str_replace(',', '', $request->amount);
        $disbursement_voucher->certified_by = $this->getSignatory('2')->employee_name;
        $disbursement_voucher->certified_by_position = $this->getSignatory('2')->employee_position;
        $disbursement_voucher->approved_by = $this->getSignatory('1')->employee_name;
        $disbursement_voucher->approved_by_position = $this->getSignatory('1')->employee_position;
        $disbursement_voucher->save();

        CacheHelper::deletePattern('swep_cache:disbursement_voucher:all:*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:byUser:'. $disbursement_voucher->user_id .':*');
        
	}



    public function onUpdate($disbursement_voucher, $request){

        $this->updateDefaults($disbursement_voucher);

        $disbursement_voucher->payee = strtoupper($request->payee);
        $disbursement_voucher->address = strtoupper($request->address);
        $disbursement_voucher->amount = str_replace(',', '', $request->amount);
        $disbursement_voucher->save();

        CacheHelper::deletePattern('swep_cache:disbursement_voucher:all:*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:byUser:'. $disbursement_voucher->user_id .':*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:bySlug:'. $disbursement_voucher->slug .'');
        
    }



    public function onDestroy($disbursement_voucher){

        CacheHelper::deletePattern('swep_cache:disbursement_voucher:all:*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:byUser:'. $disbursement_voucher->user_id .':*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:bySlug:'. $disbursement_voucher->slug .'');
        
    }



    public function onSetNo($disbursement_voucher){

        $disbursement_voucher->processed_at = $this->carbon->now();
        $disbursement_voucher->save();
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:all:*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:byUser:'. $disbursement_voucher->user_id .':*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:bySlug:'. $disbursement_voucher->slug .'');
        
    }



    public function onConfirmCheck($disbursement_voucher){

        CacheHelper::deletePattern('swep_cache:disbursement_voucher:all:*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:byUser:'. $disbursement_voucher->user_id .':*');
        CacheHelper::deletePattern('swep_cache:disbursement_voucher:bySlug:'. $disbursement_voucher->slug .'');
          
    }



	// Defaults
	public function createDefaults($disbursement_voucher){

		$disbursement_voucher->slug = $this->str->random(32);
        $disbursement_voucher->user_id = $this->auth->user()->user_id;
        $disbursement_voucher->doc_no = 'DV' . rand(10000000, 99999999);
        $disbursement_voucher->date = $this->carbon->format('Y-m-d');
        $disbursement_voucher->processed_at = null;
        $disbursement_voucher->checked_at = null;

        $disbursement_voucher->created_at = $this->carbon->now();
        $disbursement_voucher->updated_at = $this->carbon->now();
        $disbursement_voucher->ip_created = request()->ip();
        $disbursement_voucher->ip_updated = request()->ip();
        $disbursement_voucher->user_created = $this->auth->user()->username;
        $disbursement_voucher->user_updated = $this->auth->user()->username;
        $disbursement_voucher->save();

	}



    public function updateDefaults($disbursement_voucher){

        $disbursement_voucher->updated_at = $this->carbon->now();
        $disbursement_voucher->ip_updated = request()->ip();
        $disbursement_voucher->user_updated = $this->auth->user()->username;
        $disbursement_voucher->save();

    }




	// Utility Methods
	public function getSignatory($type){

		$signatory = $this->cache->remember('signatories:byType:' . $type, 240, function() use ($type){
            return $this->signatory->whereType($type)->first();
        }); 

		return $signatory;

	}




}