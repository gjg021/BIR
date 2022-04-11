<?php

/** Auth **/

use App\Swep\Helpers\Helper;
use Rats\Zkteco\Lib\ZKTeco;

Route::group(['as' => 'auth.'], function () {
	
	Route::get('/', 'Auth\LoginController@showLoginForm')->name('showLogin');
	Route::post('/', 'Auth\LoginController@login')->name('login');
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/username_lookup','Auth\AccountRecoveryController@username_lookup')->name('username_lookup');
    Route::post('/reset_password','Auth\AccountRecoveryController@reset_password')->name('reset_password');
    Route::post('/verify_email','Auth\AccountRecoveryController@verify_email')->name('verify_email');
    Route::get('/reset_password_via_email','Auth\AccountRecoveryController@reset_password_via_email')->name('reset_password_via_email');
});


/** HOME **/
Route::get('dashboard/home', 'HomeController@index')->name('dashboard.home')->middleware('check.user_status');


Route::get('/dashboard/plantilla/print','PlantillaController@print')->name('plantilla.print');

Route::group(['prefix'=>'dashboard', 'as' => 'dashboard.',
    'middleware' => ['check.user_status', 'last_activity','sidenav_mw']
], function () {
    Route::get('/dtr/my_dtr', 'DTRController@myDtr')->name('dtr.my_dtr');
    Route::get('/dtr/download','DTRController@download')->name('dtr.download');
    Route::get('/dtr/fetch_by_user_and_month', 'DTRController@fetchByUserAndMonth')->name('dtr.fetch_by_user_and_month');
    Route::post('dashboard/changePass','UserController@changePassword')->name('all.changePass');
    Route::post('/change_side_nav','SidenavController@change')->name('sidenav.change');

    Route::get('/mis_requests/my_requests','MisRequestsController@myRequests')->name('mis_requests.my_requests');
    Route::post('/mis_requests/store','MisRequestsController@store')->name('mis_requests.store');
    Route::post('/mis_requests/cancel_request','MisRequestsController@cancelRequest')->name('mis_requests.cancel_request');
    Route::get('/mis_requests/{slug}/print','MisRequestsController@printRequestForm')->name('mis_requests.print_request_form');
    Route::post('/mis_requests/store_img','MisRequestsController@storeImg')->name('mis_requests.store_img');
    Route::get('/mis_requests_status/index_open','MisRequestsStatusController@indexOpen')->name('mis_requests_status.index_open');

    /** PROFILE **/
    Route::get('/profile', 'ProfileController@details')->name('profile.details');
    Route::patch('/profile/update_account_username/{slug}', 'ProfileController@updateAccountUsername')->name('profile.update_account_username');
    Route::patch('/profile/update_account_password/{slug}', 'ProfileController@updateAccountPassword')->name('profile.update_account_password');
    Route::patch('/profile/update_account_color/{slug}', 'ProfileController@updateAccountColor')->name('profile.update_account_color');
    Route::get('/profile/print_pds/{slug}/{page}', 'ProfileController@printPds')->name('profile.print_pds');
    Route::post('/profile/save_family_info','ProfileController@saveFamilyInfo')->name('profile.save_family_info');

    Route::get('/profile/service_record','ProfileController@serviceRecord')->name('profile.service_record');
    Route::post('/profile/service_record_store','ProfileController@serviceRecordStore')->name('profile.service_record_store');
    Route::put('/profile/service_record_update/{slug}','ProfileController@serviceRecordUpdate')->name('profile.service_record_update');
    Route::delete('/profile/service_record/destroy/{slug}','ProfileController@serviceRecordDestroy')->name('profile.service_record_destroy');

    Route::get('/profile/training','ProfileController@training')->name('profile.training');
    Route::post('/profile/training_store','ProfileController@trainingStore')->name('profile.training_store');
    Route::put('/profile/training_update/{slug}','ProfileController@trainingUpdate')->name('profile.training_update');
    Route::delete('/profile/training_destroy/{slug}','ProfileController@trainingDestroy')->name('profile.training_destroy');

    Route::get('/ajax/{for}','AjaxController@get')->name('ajax.get');
    Route::post('/profile/educ_bg_store','ProfileController@educationalBackgroundStore')->name('profile.educ_bg_store');
    Route::post('/profile/eligibility_store','ProfileController@eligibilityStore')->name('profile.eligibility_store');
    Route::post('/profile/work_experience_store','ProfileController@workExperienceStore')->name('profile.work_experience_store');
});

/** Dashboard **/
Route::group(['prefix'=>'dashboard', 'as' => 'dashboard.',
    'middleware' => ['check.user_status', 'check.user_route', 'last_activity']
], function () {

	/** USER **/

	Route::post('/user/activate/{slug}', 'UserController@activate')->name('user.activate');
	Route::post('/user/deactivate/{slug}', 'UserController@deactivate')->name('user.deactivate');
	Route::get('/user/{slug}/reset_password', 'UserController@resetPassword')->name('user.reset_password');
	Route::patch('/user/reset_password/{slug}', 'UserController@resetPasswordPost')->name('user.reset_password_post');
	Route::get('/user/{slug}/sync_employee', 'UserController@syncEmployee')->name('user.sync_employee');
	Route::patch('/user/sync_employee/{slug}', 'UserController@syncEmployeePost')->name('user.sync_employee_post');
	Route::post('/user/unsync_employee/{slug}', 'UserController@unsyncEmployee')->name('user.unsync_employee');

	Route::resource('user', 'UserController');


	/** DISBURSEMENT VOUCHERS **/
	Route::get('/disbursement_voucher/user_index', 'DisbursementVoucherController@userIndex')->name('disbursement_voucher.user_index');
	Route::get('/disbursement_voucher/print/{slug}/{type}', 'DisbursementVoucherController@print')->name('disbursement_voucher.print');
    Route::get('/disbursement_voucher/print_preview/{slug}', 'DisbursementVoucherController@printPreview')->name('disbursement_voucher.print_preview');
	Route::patch('/disbursement_voucher/set_no/{slug}', 'DisbursementVoucherController@setNo')->name('disbursement_voucher.set_no_post');
	Route::post('/disbursement_voucher/confirm_check/{slug}', 'DisbursementVoucherController@confirmCheck')->name('disbursement_voucher.confirm_check');
	Route::get('/disbursement_voucher/{slug}/save_as', 'DisbursementVoucherController@saveAs')->name('disbursement_voucher.save_as');
	Route::resource('disbursement_voucher', 'DisbursementVoucherController');





	/** MENU **/
	Route::resource('menu', 'MenuController');

    /** MENU **/
    Route::get('/submenu/fetch','SubmenuController@fetch')->name('submenu.fetch');
	Route::resource('submenu','SubmenuController');

	/** SIGNATORIES **/
	Route::resource('signatory', 'SignatoryController');


	/** DEPARTMENTS **/
	Route::resource('department', 'DepartmentController');


	/** DEPARTMENT UNITS **/
	Route::resource('department_unit', 'DepartmentUnitController');


	/** PROJECT CODES **/
	Route::resource('project_code', 'ProjectCodeController');


	/** FUND SOURCE **/
	Route::resource('fund_source', 'FundSourceController');


	/** LEAVE APPLICATION **/
	Route::get('/leave_application/user_index', 'LeaveApplicationController@userIndex')->name('leave_application.user_index');
	Route::get('/leave_application/print/{slug}/{type}', 'LeaveApplicationController@print')->name('leave_application.print');
	Route::get('/leave_application/{slug}/save_as', 'LeaveApplicationController@saveAs')->name('leave_application.save_as');
	Route::resource('leave_application', 'LeaveApplicationController');


	/** EMPLOYEE **/
    Route::get('/employee/edit_bm_uid','EmployeeController@edit_bm_uid')->name('employee.edit_bm_uid');
    Route::post('/employee/update_bm_uid','EmployeeController@update_bm_uid')->name('employee.update_bm_uid');
	Route::get('/employee/print_pds/{slug}/{page}', 'EmployeeController@printPds')->name('employee.print_pds');
	
	Route::get('/employee/service_record/{slug}', 'EmployeeController@serviceRecord')->name('employee.service_record');
	Route::post('/employee/service_record/store/{slug}', 'EmployeeController@serviceRecordStore')->name('employee.service_record_store');
	Route::put('/employee/service_record/update/{slug}', 'EmployeeController@serviceRecordUpdate')->name('employee.service_record_update');
	Route::delete('/employee/service_record/destroy/{slug}', 'EmployeeController@serviceRecordDestroy')->name('employee.service_record_destroy');
	Route::get('/employee/service_record/print/{slug}', 'EmployeeController@serviceRecordPrint')->name('employee.service_record_print');

	Route::get('/employee/training/{slug}', 'EmployeeController@training')->name('employee.training');
	Route::post('/employee/training/store/{slug}', 'EmployeeController@trainingStore')->name('employee.training_store');
	Route::put('/employee/training/update/{slug}', 'EmployeeController@trainingUpdate')->name('employee.training_update');
	Route::delete('/employee/training/destroy/{slug}', 'EmployeeController@trainingDestroy')->name('employee.training_destroy');
	Route::get('/employee/training/print/{slug}', 'EmployeeController@trainingPrint')->name('employee.training_print');

	Route::get('/employee/matrix/{slug}', 'EmployeeController@matrix')->name('employee.matrix');
	Route::post('/employee/matrix/update/{slug}', 'EmployeeController@matrixUpdate')->name('employee.matrix_update');
	Route::get('/employee/matrix/show/{slug}', 'EmployeeController@matrixShow')->name('employee.matrix_show');
	Route::get('/employee/matrix/print/{slug}', 'EmployeeController@matrixPrint')->name('employee.matrix_print');

	Route::get('/employee/report', 'EmployeeController@report')->name('employee.report');
	Route::get('/employee/report_generate', 'EmployeeController@reportGenerate')->name('employee.report_generate');
	Route::resource('employee', 'EmployeeController');


	/** DOCUMENTS **/
	Route::get('/document/report', 'DocumentController@report')->name('document.report');
	Route::get('/document/report_generate', 'DocumentController@report_generate')->name('document.report_generate');

	Route::get('/document/view_file/{slug}', 'DocumentController@viewFile')->name('document.view_file');
	Route::get('/document/download', 'DocumentController@download')->name('document.download');
	Route::post('/document/download_direct/{slug}', 'DocumentController@downloadDirect')->name('document.download_direct');
	Route::get('/document/dissemination/{slug}', 'DocumentController@dissemination')->name('document.dissemination');
	Route::post('/document/dissemination_post/{slug}', 'DocumentController@disseminationPost')->name('document.dissemination_post');

	Route::get('/document/rename_all', 'DocumentController@rename_all')->name('document.rename_all');

	Route::resource('document', 'DocumentController');

	Route::get('/document/dissemination/print/{slug}', 'DocumentController@print')->name('document.dissemination.print');

	




	/** Document Folder Codes **/
	Route::get('/document_folder/browse/{folder_code}', 'DocumentFolderController@browse')->name('document_folder.browse');
	Route::resource('document_folder', 'DocumentFolderController');


	/** Email Contacts **/
	Route::resource('email_contact', 'EmailContactController');


	/** Permission Slip **/
	Route::get('/permission_slip/report', 'PermissionSlipController@report')->name('permission_slip.report');
	Route::get('/permission_slip/report_generate', 'PermissionSlipController@reportGenerate')->name('permission_slip.report_generate');
	Route::resource('permission_slip', 'PermissionSlipController');


	/** Leave Card **/
	Route::get('/leave_card/report', 'LeaveCardController@report')->name('leave_card.report');
	Route::get('/leave_card/report_generate', 'LeaveCardController@reportGenerate')->name('leave_card.report_generate');
	Route::resource('leave_card', 'LeaveCardController');


	/** Applicant **/
	Route::post('/applicant/addToShortList/{slug}', 'ApplicantController@addToShortList')->name('applicant.add_to_shortlist');
	Route::post('/applicant/removeToShortList/{slug}', 'ApplicantController@removeToShortList')->name('applicant.remove_to_shortlist');
	Route::get('/applicant/report', 'ApplicantController@report')->name('applicant.report');
	Route::get('/applicant/report_generate', 'ApplicantController@reportGenerate')->name('applicant.report_generate');
	Route::resource('applicant', 'ApplicantController');


	/** Course **/
	Route::resource('course', 'CourseController');


	/** Plantilla **/

	Route::resource('plantilla', 'PlantillaController');


    /** Activity Logs **/
    Route::get('/activity_logs/fetch_properties', 'ActivityLogsController@fetch_properties')->name('activity_logs_fetch_properties');

    /** PAP **/
    Route::resource('pap', 'PapController');

    /** PAP  Parents**/
    Route::resource('pap_parent', 'PapParentController');

    Route::resource('ppmp', 'PPMPController');

    /** DTR **/
    Route::get('/dtr/extract', 'DTRController@extract2')->name('dtr.extract');
    Route::get('/dtr/reconstruct', 'DTRController@reconstruct')->name('dtr.reconstruct');
//    Route::get('/dtr/my_dtr', 'DTRController@myDtr')->name('dtr.my_dtr');
//    Route::post('/dtr/download','DTRController@download')->name('dtr.download');

    Route::resource('dtr', 'DTRController');

    /** DTR **/
    Route::resource('jo_employees','JOEmployeesController');

    /** DTR **/
    Route::get('holidays/fetch_google','HolidayController@fetchGoogleApi')->name('holidays.fetch_google');
    Route::resource('holidays','HolidayController');

    /** Biometric Devices **/
    Route::get('biometric_devices','BiometricDevicesController@index')->name('biometric_devices.index');
    Route::post('biometric_devices/extract','BiometricDevicesController@extract')->name('biometric_devices.extract');
    Route::post('biometric_devices/restart','BiometricDevicesController@restart')->name('biometric_devices.restart');
    Route::get('biometric_devices/attendances','BiometricDevicesController@attendances')->name('biometric_devices.attendances');
    Route::post('biometric_devices/clear_attendance','BiometricDevicesController@clear_attendance')->name('biometric_devices.clear_attendance');

    Route::get('mis_requests','MisRequestsController@index')->name('mis_requests.index');
    Route::get('mis_requests/{slug}/edit','MisRequestsController@edit')->name('mis_requests.edit');
    Route::put('mis_requests/{request_slug}/update','MisRequestsController@update')->name('mis_requests.update');
    Route::resource('mis_requests_status','MisRequestsStatusController');

    /** Budget Proposal**/
    Route::resource('budget_proposal', 'BudgetProposalController');

    /** PPMP **/
    Route::resource('ppmp', 'PPMPController');
});





Route::get('/dtr', function (){
    return redirect('/dashboard/dtr/my_dtr');
});
Route::get('/assign',function (){
    $users = \App\Models\User::query()->where('user_id','=','')->get();
    foreach ($users as $user){
        $user->user_id = rand(1111111,9999999);
        $user->update();
    }
    return 1;
});

Route::get('/dashboard/compute', function (\App\Swep\Services\DTRService $service){

    return $service->compute();


});

Route::get('/dashboard/tree', function (){
    return view('dashboard.blank');
});

Route::get('/file_explorer',function (){

   return view('dashboard.file_explorer.index');
})->name('dashboard.documents.file_explorer.index');

/** Test Route **/

Route::get('/dashboard/test', function(){

//    $zk = new ZKTeco('10.36.1.23');
//    //ini_set('max_execution_time', 300);
//    $zk->connect();
//    $zk->testVoice();
//    $zk->setTime('2022-01-04 14:59:03');
//
//    $zk->disconnect();

	return dd([
	    'slug' => Illuminate\Support\Str::random(16),
        'small' => strtoupper(Illuminate\Support\Str::random(7)),
    ]);

});

Route::get('dashboard/prayer', function (){
    $path = asset('json/quotes.json');
    $content = json_decode(file_get_contents($path),true);

    $today = Carbon::now()->format('Y-m-d');
    $qod_db = \App\Models\QuoteOfTheDay::query()->where('date',$today)->first();
    if(empty($qod_db)){
        $random = rand(0,1643);
        $qod = new \App\Models\QuoteOfTheDay();
        $qod->quote = $random;
        $qod->date = $today;
        $qod->save();

    }
    $qod_db_2 = \App\Models\QuoteOfTheDay::query()->where('date',$today)->first();
    return view('dashboard.prayer.index')->with([
        'qod' => $content[$qod_db_2->quote],
    ]);
})->name('dashboard.prayer');

Route::get('dashboard/zk_test',function (){

    $zk = new \Rats\Zkteco\Lib\ZKTeco('10.36.1.21');
    $zk->connect();

    return $zk->getUser();
    //return $zk->serialNumber();
});


Route::get('jo',function (){
   return view('dashboard.public.jo_entry')->with([
       'user_menus_records ' => '',

   ]);
});

Route::get('check_device',function (\App\Swep\Services\DTRService $DTRService){
    if(!request()->has('ip')){
        return 'Missing IP';
    }
    $ip = request()->get('ip');
    $zk = new ZKTeco($ip);
    $zk->connect();
    return $zk->getAttendance();
//    return $DTRService->clearAttendance('10.36.1.23');
});

Route::get('dashboard/set', function (){
    if(request()->ajax()){
        if(request()->has('set')){
            $zk = new ZKTeco('10.36.1.'.request()->get('dev'));
            $zk->connect();
            $zk->setTime(request()->get('date').' '.request()->get('time'));
            return 1;
        }
        if (request()->has('reset')){
            $zk = new ZKTeco('10.36.1.'.request()->get('dev'));
            $zk->connect();
            $zk->setTime(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
            return 1;
        }
        if(request()->has('verify')){
            if(request()->get('password') === 'superadmin'){
                request()->session()->put('verify',['expires_on'=>\Carbon\Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s'),'type'=>'su']);
                return 1;
            }
            if(request()->get('password') ==='misvis'){
                request()->session()->put('verify',['expires_on'=>\Carbon\Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s'),'type'=>'u']);
                return 1;
            }
        }
    }

    if (request()->session()->exists('verify')) {
        if(request()->session()->get('verify')['expires_on'] < \Carbon\Carbon::now()->format('Y-m-d H:i:s')){
            request()->session()->forget('verify');
            return view('dashboard.set.verify');
        }else{
            request()->session()->get('verify')['type'];
            if(request()->session()->get('verify')['type'] == 'su'){
                return view('dashboard.set.index');
            }elseif(request()->session()->get('verify')['type'] == 'u'){
                return view('dashboard.set.lower');
            }
        }
    }


    return view('dashboard.set.verify');

})->name('dashboard.set');

Route::get('/pdo',function(){

    $db = \App\Models\SqlServer\EmpMaster::query()->where('EmpNo','=','0090-8')->first();
    return dd($db);
    $serverName = "10.36.1.105\SRA";

    $connectionInfo = array("Database"=>"GASS","UID" => "sa", "PWD" => 'noliboy');
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if($conn){
        echo "Connection established. <br>";
        $tsql = "SELECT * FROM dbo.EmpMaster";

        $stmt = sqlsrv_query( $conn, $tsql );

        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            echo $row['LastName'].", ".$row['FirstName']."<br />";
        }

        sqlsrv_free_stmt( $stmt);
        

    }else{
        echo "Connection failed: <br>";
        die(print_r(sqlsrv_errors(), true));
    }
});

Route::get('/phpinfo',function (){
    echo phpinfo();
});

Route::get('/fix_dates',function (Request $request){

    if(request('no')== 1){
        $srs = \App\Models\EmployeeServiceRecord::query()
            ->where('date_to','!=' ,'99/99/99')
            ->where('date_to','!=','00/00/00')
            ->where('date_to','!=','00/00/0000')
            ->where('date_to','!=','')
            ->get();
        $ct = 0;
        foreach ($srs as $sr){
            if (DateTime::createFromFormat('m/d/Y', $sr->date_to) !== false && $sr->date_to != '99/99/99' && $sr->date_to != '//') {
                $sr->to_date = \Illuminate\Support\Carbon::parse($sr->date_to)->format('Y-m-d');
                $sr->update();
            }
        }

        return 'COpied';
    }

    if(request('no')== 2){
        $srs = \App\Models\EmployeeServiceRecord::query()
            ->where('date_from','!=' ,'99/99/99')
            ->where('date_from','!=','00/00/00')
            ->where('date_from','!=','00/00/0000')
            ->where('date_from','!=','')
            ->get();
        $ct = 0;
        foreach ($srs as $sr){
            if (DateTime::createFromFormat('m/d/Y', $sr->date_from) !== false && $sr->date_from != '99/99/99' && $sr->date_from != '//') {
                $sr->from_date = \Illuminate\Support\Carbon::parse($sr->date_from)->format('Y-m-d');
                $sr->update();
            }
        }

        return 'COpied';
    }
    return 111;
});

//Route::get('/migrate_cos',function (){
//   $coss = \App\Models\JoEmployees::query()->get();
//   $employee = \App\Models\Employee::query();
//   $empsArr = [];
//   $addressesArr = [];
//   foreach ($coss as $cos){
//       $cosArr = [
//           'employee_no' => $cos->employee_no,
//           'firstname' => strtoupper($cos->firstname),
//           'middlename' => strtoupper($cos->middlename),
//           'lastname' => strtoupper($cos->lastname),
//           'biometric_user_id' => $cos->biometric_user_id,
//           'created_at' => $cos->created_at,
//           'updated_at' => $cos->updated_at,
//           'name_ext' => $cos->name_ext,
//           'sex' => $cos->sex,
//           'civil_status' => strtoupper($cos->civil_status),
//           'date_of_birth' => $cos->birthday,
//           'email' => $cos->email,
//           'cell_no' => $cos->phone,
//           'department_unit_id' => $cos->department_unit,
//           'position' => $cos->position,
//           'user_created' => $cos->user_created,
//           'user_updated' => $cos->user_updated,
//           'ip_created'=> $cos->ip_created,
//           'ip_updated' => $cos->ip_updated,
//           'slug' => $cos->slug,
//           'locations' => 'COS',
//           'is_active' => 'ACTIVE',
//           'fullname' => strtoupper($cos->lastname).', '.strtoupper($cos->firstname),
//       ];
//       $cos_add = [
//            'employee_no'=> $cos->employee_no,
//            'address_detailed' => $cos->address_detailed,
//            'res_address_barangay' => $cos->brgy,
//            'res_address_city' => $cos->city,
//            'res_address_province' => $cos->province,
//       ];
//       array_push($empsArr,$cosArr);
//       array_push($addressesArr,$cos_add);
//   }
//   $employee->insert($empsArr);
////   \App\Models\EmployeeAddress::query()->insert($addressesArr);
//
//   return 1;
//});
//
//Route::get('/update_locations',function (){
//    $emps = \App\Models\Employee::query()->where('locations','=',null)->get();
//    foreach ($emps as $emp){
//        if(!empty($emp->empMaster)){
//            $emp->locations = $emp->empMaster->LOCATION;
//            $emp->update();
//        }
//    }
//});
//
//Route::get('/update_emp_no',function (){
//    $emps = \App\Models\Employee::query()->where('locations','=','COS')->get();
//    foreach ($emps as $emp){
//        $new_emp_no = str_replace('221','222',$emp->employee_no);
//
//        $emp->employeeAddress()->update([
//            'employee_no' => $new_emp_no,
//        ]);
//        $emp->user()->update([
//            'employee_no' => $new_emp_no,
//        ]);
//        $emp->dtr_records()->update([
//            'employee_no' => $new_emp_no,
//        ]);
//
//        $emp->documentDisseminationLog()->update([
//            'employee_no' => $new_emp_no,
//        ]);
//        $emp->employee_no = $new_emp_no;
//        $emp->update();
//
//    }
//    return 1;
//});


Route::get('/import_service_records',function (){
    $dbs = \App\Models\HrEmployeesSRTemp::query()->where('from_date','=',null)->get();
    foreach ($dbs as $db){
        if (DateTime::createFromFormat('m/d/Y', $db->date_from) !== false && $db->date_from != '99/99/99' && $db->date_from != '//') {
            $db->from_date = \Illuminate\Support\Carbon::parse($db->date_from)->format('Y-m-d');
            $db->update();
        }
    }

    $dbs = \App\Models\HrEmployeesSRTemp::query()->where('to_date','=',null)->get();

    foreach ($dbs as $db){
        if (DateTime::createFromFormat('m/d/Y', $db->date_to) !== false && $db->date_to != '99/99/99' && $db->date_to != '99/99/9999' && $db->date_to != '//') {
            $db->to_date = \Illuminate\Support\Carbon::parse($db->date_to)->format('Y-m-d');
            $db->update();
        }
    }

    $dbs = \App\Models\HrEmployeesSRTemp::query()->where('gov_serve','!=',null)->where('gov_serve','!=','')->get();

    foreach ($dbs as $db){
        if (DateTime::createFromFormat('m/d/Y', $db->gov_serve) !== false && $db->gov_serve != '99/99/99' && $db->gov_serve != '99/99/9999' && $db->gov_serve != '//') {
            $db->gov_serve = Carbon::parse($db->gov_serve)->format('Y-m-d');
            $db->update();
        }else{
//            return $db;
        }
    }

    $dbs = \App\Models\HrEmployeesSRTemp::query()->where('psc_serve','!=',null)->where('psc_serve','!=','')->get();

    foreach ($dbs as $db){
        if (DateTime::createFromFormat('m/d/Y', $db->psc_serve) !== false && $db->psc_serve != '99/99/99' && $db->psc_serve != '99/99/9999' && $db->psc_serve != '//') {
            $db->psc_serve = Carbon::parse($db->psc_serve)->format('Y-m-d');
            $db->update();
        }else{
//            return $db;
        }
    }
    $dbs = \App\Models\HrEmployeesSRTemp::query();
    $dbs->update([
        'remarks' => 'IMPORTED',
    ]);

    $arr = [];
    $arr[0] = [];
    $arr[1] = [];
    $arr[2] = [];
    $arr[3] = [];
    $arr[4] = [];
    $arr[5] = [];
    $dbs = \App\Models\HrEmployeesSRTemp::query()->get();
    $num = 0;
    $key = 0;
    foreach ($dbs as $db){
        $num++;
        switch ($num){
            case $num > 4999 : $key = 5; break;
            case $num > 3999 : $key = 4; break;
            case $num > 2999 : $key = 3; break;
            case $num > 1999 : $key = 2; break;
            case $num > 999 : $key = 1; break;

        }

        array_push($arr[$key],[
            'slug' => \Illuminate\Support\Str::random(16),
            'employee_no' => $db->employee_no,
            'sequence_no' => $db->sequence_no,
            'date_from' => $db->date_from,
            'from_date' => $db->from_date,
            'date_to' => $db->date_to,
            'to_date' => $db->to_date,
            'upto_date' => $db->upto_date,
            'position' => $db->position,
            'appointment_status' => $db->appointment_status,
            'salary' => $db->salary,
            'mode_of_payment' => $db->mode_of_payment,
            'station' => $db->station,
            'gov_serve' => $db->gov_serve,
            'psc_serve' => $db->psc_serve,
            'lwp' => $db->lwp,
            'spdate' => $db->spdate,
            'status' => $db->status,
            'remarks' => $db->remarks,
            'user_created' => 'SYSTEM',
        ]);
    }

    foreach ($arr as $ar){
        $ins = \App\Models\EmployeeServiceRecord::query()->insert($ar);

    }
    return 'DONE';

});

Route::get('/trainings',function (){
    $grp = \App\Models\EmployeeTraining::query()
        ->where('date_from','!=',null)
        ->where('sequence_no','=',null)
        ->groupBy('employee_no')
        ->orderBy('id','asc')
        ->get();

    foreach ($grp as $emp){

        $ts = \App\Models\EmployeeTraining::query()
            ->where('employee_no','=',$emp->employee_no)
            ->orderBy('date_from','asc')->get();
        $sn = 10;
        foreach ($ts as $t){
            $t->sequence_no = $sn;
            $t->update();
            $sn = $sn+10;
        }
    }
    $ts = \App\Models\EmployeeTraining::query()
        ->where('date_from','!=',null)
        ->where('date_to','!=',null)
        ->get();
    foreach ($ts as $t){
        if($t->date_from != $t->date_to){
            $t->detailed_period = \Illuminate\Support\Carbon::parse($t->date_from)->format('F d, Y').' - '.\Illuminate\Support\Carbon::parse($t->date_to)->format('F d, Y');
            $t->update();
        }else{
            $t->detailed_period = \Illuminate\Support\Carbon::parse($t->date_from)->format('F d, Y');
            $t->update();
        }
    }
    return 1000;
});

Route::get('/trainings_t_to_t',function (){
    $ts = \App\Models\HRTrainingsTemp::query()->get();
    $arr[0] = [];
    $arr[1] = [];
    $arr[2] = [];
    $arr[3] = [];
    $arr[4] = [];
    $arr[5] = [];
    $num = 0;
    foreach ($ts as $t){
        $key = 0;
        switch ($num){
            case ($num < 1000): $key = 0;
                break;
            case ($num < 2000): $key = 1;
                break;
            case ($num < 3000): $key = 2;
                break;
            case ($num < 4000): $key = 3;
                break;
            case ($num < 5000): $key = 4;
                break;
            case ($num < 6000): $key = 5;
                break;
        }
        $num++;
        array_push($arr[$key],[
            'employee_no' => $t->employee_no,
            'slug' => \Illuminate\Support\Str::random(),
            'sequence_no' => $t->sequence_no,
            'title' => $t->title,
            'type' => $t->type,
            'date_from' => $t->date_from,
            'date_to' => $t->date_to,
            'detailed_period' => $t->period,
            'hours' => $t->hours,
            'conducted_by' => $t->conducted_by,
            'venue' => $t->venue,
            'user_created' => 'SYSTEM',
        ]);
    }
    foreach ($arr as $ar){
        \App\Models\EmployeeTraining::query()->insert($ar);
    }
    return 'DONE';
});

Route::get('/checking',function (){
    return 1;
    $sqlsrv_emps_arr = [];
    $emps_arr = [];
    $sqlsrv_emps = \App\Models\SqlServer\EmpMaster::query()->get();
    foreach ($sqlsrv_emps as $sqlsrv_emp){
        array_push($sqlsrv_emps_arr,$sqlsrv_emp->EmpNo);
    }
    $emps = \App\Models\Employee::query()->get();
    foreach ($emps as $emp){
        array_push($emps_arr,$emp->employee_no);
    }
    $diff = array_diff($sqlsrv_emps_arr,$emps_arr);

    foreach ($diff as $diff_emp_no){
        $sql_srv_e = \App\Models\SqlServer\EmpMaster::query()->where('EmpNo','=',$diff_emp_no)->first();
        $e = \App\Models\Employee::query()->where('lastname','=',$sql_srv_e->LastName)
            ->where('firstname','=',$sql_srv_e->FirstName)->first();
        if(!empty($e)){

            $e->user()->update(['employee_no' => $sql_srv_e->EmpNo]);
            $e->employeeTraining()->update(['employee_no' => $sql_srv_e->EmpNo]);
            $e->dtr_records()->update(['employee_no' => $sql_srv_e->EmpNo]);
            $e->documentDisseminationLog()->update(['employee_no' => $sql_srv_e->EmpNo]);
            $e->employee_no = $sql_srv_e->EmpNo;
            $e->update();
            return $e;
        }else{
            $e = new \App\Models\Employee;
            $e->employee_no = $sql_srv_e->EmpNo;
            $e->lastname = $sql_srv_e->LastName;
            $e->slug = \Illuminate\Support\Str::random();
            $e->firstname = $sql_srv_e->FirstName;
            $e->middlename = $sql_srv_e->MiddleName;
            $e->department_id = $sql_srv_e->Division;
            $e->department_unit_id = $sql_srv_e->Dept;
            $e->fullname = $sql_srv_e->EmpName;
            $e->date_of_birth = Carbon::parse($sql_srv_e->DOB)->format('Y-m-d');
            $e->place_of_birth = $sql_srv_e->POB;
            $e->sex = $sql_srv_e->Gender;
            $e->civil_status = $sql_srv_e->CivilStat;
            $e->blood_type = $sql_srv_e->BloodType;
            $e->gsis = $sql_srv_e->GSIS;
            $e->philhealth = $sql_srv_e->PHIC;
            $e->tin = $sql_srv_e->TIN;
            $e->hdmf = $sql_srv_e->HDMF;
            $e->hdmfpremiums = $sql_srv_e->HDMFPREMIUMS;
            $e->appointment_status = $sql_srv_e->ApptStat;
            $e->position = $sql_srv_e->Position;
            $e->item_no = $sql_srv_e->ItemNo;
            $e->salary_grade = $sql_srv_e->SalGrade;
            $e->step_inc = $sql_srv_e->StepInc;
            $e->monthly_basic = $sql_srv_e->MonthlyBasic;
            $e->aca = $sql_srv_e->ACA;
            $e->pera = $sql_srv_e->PERA;
            $e->food_subsidy = $sql_srv_e->FoodSubsi;
            $e->ra = $sql_srv_e->RA;
            $e->ta= $sql_srv_e->TA;
            $e->firstday_gov = $sql_srv_e->GovServ;
            $e->firstday_sra = $sql_srv_e->FirstDay;
            $e->appointment_date = $sql_srv_e->ApptDate;
            $e->adjustment_date = $sql_srv_e->AdjDate;
            $e->is_active = $sql_srv_e->ACTIVE;
            $e->user_created = 'SYSTEM';
            $e->locations = $sql_srv_e->LOCATION;
            $e->save();

        }
    }
});

Route::get('/copy_depts',function (){
//   $depts = DB::connection('sqlsrv')->table('dbo.RespCtr')->get();
//
//   $arr = [];
//   foreach ($depts as $dept){
//       array_push($arr,[
//           'slug' => \Illuminate\Support\Str::random(),
//           'department_id' => $dept->RSCode,
//            'department_name' => $dept->DEPTNAME,
//           'user_created' => 'SYSTEM',
//       ]);
//   }
//    \App\Models\Department::query()->insert($arr);
});

Route::get('/copy_dept_units',function (){
//    $dus = DB::connection('sqlsrv')->table('dbo.Depts')->get();
//    $arr = [];
//    foreach ($dus as $du){
//        array_push($arr,[
//            'slug' => \Illuminate\Support\Str::random(),
//            'department_id' => $du->DeptID,
//            'department_unit_id' => $du->DeptUnit,
//            'department_unit_name' => $du->LongName,
//            'department_no' => $du->DEPTNO,
//            'user_created' => 'SYSTEM',
//        ]);
//    }
//    \App\Models\DepartmentUnit::query()->insert($arr);
});

Route::get('/import_plantilla',function (){
   $sqlsrv = DB::connection('sqlsrv')->table('dbo.PayPLANTILLA')->get();
   $plantillaArr = [];
   foreach ($sqlsrv as $item){
       $arr = [
           'location' => $item->Location,
           'item_no' => $item->ItemNo,
           'department_header' => $item->DeptHeader,
           'department' => $item->Dept,
           'division_header' => $item->DivisionHeader,
           'division' => $item->Division,
           'section_header' => $item->SectionHeader,
           'section' => $item->Sectionss,
           'position' => $item->Position,
           'employee_name' => $item->EmpName,
           'job_grade' => $item->JobGrade,
           'step_inc' => $item->StepInc,
           'actual_salary' => $item->ActualSalary,
           'actual_salary_gcg' => $item->ActualSalaryGCG,
           'eligibility' => $item->Eligibility,
           'educ_att' => $item->EducAttain,
           'appointment_status' => $item->ApptStat,
           'appointment_date' => $item->ApptDate,
           'last_promotion' => $item->LastPromote,
           'seq_groupings' => $item->SeqGroupings,
           'div_groupings' => $item->DivGroupings,
           'original_job_grade' => $item->OriginalJG,
           'original_job_grade_si' => $item->OriginalSI,
           'original_salary_grade' => $item->OriginalSG,
           'original_salary_grade_si' => $item->OriginalSGSI,
           'control_no' => $item->CONTROLNO,
           'user_created' => 'SYSTEM',
       ];
        array_push($plantillaArr,$arr);
   }
    \App\Models\HRPayPlanitilla::query()->insert($plantillaArr);
});

Route::get('/get_emp_no',function (){
    $pls = \App\Models\HRPayPlanitilla::query()->get();
    foreach ($pls as $pl){
        if($pl->employee_name != null){
            $cut = substr($pl->employee_name,0,15);
            $emp = \App\Models\Employee::query()->where(DB::raw('CONCAT(lastname,", ",firstname)'),'like','%'.$cut.'%')->first();
            if(!empty($emp)){
                $pl->employee_no = $emp->employee_no;
                $pl->update();
            }
        }
    }

    $pls = \App\Models\HRPayPlanitilla::query()->where('employee_no','=',null)->get();
    foreach ($pls as $pl){
        if($pl->employee_name != null){
            $cut = substr($pl->employee_name,0,11);
            $emp = \App\Models\Employee::query()->where(DB::raw('CONCAT(lastname,", ",firstname)'),'like','%'.$cut.'%')->first();
            if(!empty($emp)){
                $pl->employee_no = $emp->employee_no;
                $pl->update();
            }
        }
    }
});

