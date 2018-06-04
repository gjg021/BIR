<?php

namespace App\Swep\ViewComposers;


use View;
use App\Models\ModeOfPayment;
use Illuminate\Cache\Repository as Cache;


class ModeOfPaymentComposer{
   

	protected $mode_of_payment;
	protected $cache;


	public function __construct(ModeOfPayment $mode_of_payment, Cache $cache){
		$this->mode_of_payment = $mode_of_payment;
		$this->cache = $cache;
	}



    public function compose($view){

        $mode_of_payment = $this->cache->remember('modes_of_payment:global:all', 240, function(){
        	return $this->mode_of_payment->select('mode_of_payment_id', 'description')->get();
        });
        
    	$view->with('global_mode_of_payment_all', $mode_of_payment);

    }




}