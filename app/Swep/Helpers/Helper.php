<?php


namespace App\Swep\Helpers;
use App\Models\Department;
use App\Models\DocumentFolder;
use App\Models\MisRequestsNature;
use App\Models\PPU\RecommendedBudget;
use App\Models\SuSettings;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Helper
{

    public static function user_colors(){

        return [

            'Blue/Dark' => 'sidebar-mini skin-blue',
            'White/Dark' => 'sidebar-mini skin-black',
            'Purple/Dark' => 'sidebar-mini skin-purple',
            'Green/Dark' => 'sidebar-mini skin-green',
            'Red/Dark' => 'sidebar-mini skin-red',
            'Yellow/Dark' => 'sidebar-mini skin-yellow',
            'Blue/Light' => 'sidebar-mini skin-blue-light',
            'White/Light' => 'sidebar-mini skin-black-light',
            'Purple/Light' => 'sidebar-mini skin-purple-light',
            'Green/Light' => 'sidebar-mini skin-green-light',
            'Red/Light' => 'sidebar-mini skin-red-light',
            'Yellow/Light' => 'sidebar-mini skin-yellow-light',

        ];
    }


    public static function online_badge($lastActivity,$fullwidth = true){

        if($fullwidth == true){
            $cols = 'col-md-12';
            $br = '</br>';
        }else{
            $cols = '';
            $br = '';
        }
        if($lastActivity == null){
            return '<span class="label bg-gray '.$cols.'">OFFLINE</span>';
        }else{
            $last_activity = Carbon::parse($lastActivity);
            if($last_activity->diffInSeconds() < 301){
                return '<span class="label bg-green '.$cols.'">ONLINE</span>';
            }else{
                if($last_activity->diffInMinutes() < 60){
                    return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInMinutes().' minutes ago</span>';
                }else{
                    if($last_activity->diffInMinutes() >= 60){
                        if($last_activity->diffInHours() < 2){
                            return '<span class="label bg-gray '.$cols.'">Active an hour ago</span>';
                        }else{
                            if($last_activity->diffInHours() > 23){
                                if($last_activity->diffInDays() < 2){
                                    return '<span class="label bg-gray '.$cols.'">Active a day ago</span>';
                                }else{
                                    return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInDays().' days ago</span>';
                                }
                            }
                            return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInHours().' hours ago</span>';
                        }
                    }
                }
            }
        }

    }


    public static function folderCodesArray(){
        $folders = DocumentFolder::query()->orderBy('folder_code','asc')->get();
        if(!empty($folders)){
            $opt = '';
                $new_arr = [];
                foreach ($folders as $folder){
                    $new_arr[$folder->folder_code] = $folder->folder_code.' - '.$folder->description;
//                array_push($new_arr,[]);
                }


            return $new_arr;
        }
        return [];
    }
    public static function dtr_type($type){
        $types = [
            10 => 'Check in',
            30 => 'Break out',
            20 => 'Break in',
            40 => 'Check out',
            50 => 'Overtime in',
            60 => 'Overtime Out',
        ];
        if(!is_int($type)){
            return 'Must be integer';
        }else{
            if($type < 0 && $type > 5){
                return 'Invalid parameter';
            }else{
                return $types[$type];
            }
        }
    }
    public static function dtrTypeToInt(){
        return [
            'am_in' => 10,
            'am_out' => 20,
            'pm_in' => 30,
            'pm_out' => 40,
            'ot_in' => 50,
            'ot_out' => 60,
        ];
    }

    public static function dtrIntToField(){
        return [
            10 => 'am_in',
            20 => 'am_out',
            30 => 'pm_in',
            40 => 'pm_out',
            50 => 'ot_in',
            60 => 'ot_out',
        ];
    }

    public static function name_extensions(){
        return [
            'SR' => 'SR',
            'JR' => 'JR',
            'I' => 'I',
            'II' => 'II',
            'III' => 'III',
            'IV' => 'IV',
            'V' => 'V',
        ];
    }

    public static function sex(){
        return [
            'MALE' => 'MALE',
            'FEMALE' => 'FEMALE',
        ];
    }

    public static function civil_status(){
        return [
            'Single' => 'Single',
            'Married' => 'Married',
            'Widowed' => 'Widowed',
            'Divorced' => 'Divorced',
            'Separated' => 'Separated',
        ];
    }

    public static function blood_types(){
        return [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
        ];
    }

    public static function holiday_types(){
        return [
            'Public holiday' => 'Public holiday',
            'Regular holiday' => 'Regular holiday',
            'Special Non-working holiday' => 'Special Non-working holiday',
            'Observances' => 'Observances',
            'Office declaration' => 'Office declaration',
            'Work Suspension' => 'Work Suspension',
        ];
    }

    public static function implode_assoc($arr){
        $string = '';

        foreach ($arr as $data){
            $string = $string.$data.',';
        }
        return $string;
    }

    public static function getStingAfterChar($subject, $character){
        $whatIWant = substr($subject, strpos($subject, $character) + 1);
        return $whatIWant;
    }

    public static function departmentUnitArrayForSelect(){
        $d = \App\Models\Department::get();
        $department_array = [];
        foreach ($d as $dept){

            foreach($dept->departmentUnit as $unit){
                $department_array[$dept->name][$unit->department_unit_id] = $unit->description;
            }


        }
        foreach ($department_array  as $key=> $units) {
            ksort($department_array[$key]);
        }
        return $department_array;
    }

    public static function sexArray(){
        return [
            'FEMALE' => 'Female',
            'MALE' => 'Male',
        ];
    }

    public static  function acronym($string){
        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0] ?? 'UNKNOWN';
        }
        return $acronym;
    }

    public static function getUserName(){
        $employee = Auth::user()->employee;
        $auth = Auth::user();
        $firstname = $employee->firstname ?? $auth->firstname;
        $middlename = $employee->middlename ?? $auth->middlename;
        $lastname = $employee->lastname ?? $auth->lastname;
        $position = $employee->position ?? $auth->position;


        return [
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'position' => $position,
        ];
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function biometricValuesColor($val){
        $values = [
            10 => '#0073b7',
            20 => '#d76d00',
            30 => '#00a65a',
            40 => '#8eaa1d',
            50 => '#7c54f5',
            60 => '#4a24bf',
        ];

        return$values[$val];
    }
    public static function sanitizeNumFormat($num){
        return str_replace(',','',$num);
    }

    public static function sanitizeAutonum($num){
        $num = str_replace('₱','',$num);
        return str_replace(',','',$num);
    }

    public static function mis_request_nature(){
        $natures = MisRequestsNature::query()->get();
        $array = [];
        if(!empty($natures)){

            foreach ($natures as $nature){
                $array[$nature->group][$nature->nature_of_request] = $nature->slug;
            }
        }
        return $array;
    }


    public static function formatBytes($size,$unit) {
        if($unit == "KB")
        {
            return $fileSize = round($size / 1024,2) . ' KB';
        }
        if($unit == "MB")
        {
            return $fileSize = round($size / 1024 / 1024,2) . ' MB';
        }
        if($unit == "GB")
        {
            return $fileSize = round($size / 1024 / 1024 / 1024,2) . ' GB';
        }
    }

    public static function responsibilityCenters(){
        $arr = [
            'PPSPD' => 'PPSPD',
            'AFD' => 'AFD',
            'RDE' => 'RDE',
            'REGULATION' => 'REGULATION',
            'OB' => 'OB',
            'OA' => 'OA',
            'IAD'=>'IAD',
            'LEGAL' => 'LEGAL',
        ];
        ksort($arr);
        return $arr;
    }
    public static function budgetTypes(){
        return [
            'COB' => 'Corporate',
            'SIDA' => 'SIDA',
        ];
    }

    public static function stations(){
        return [
            'Bacolod City' => 'Bacolod City',
            'Quezon City' => 'Quezon City',
        ];
    }



    public static function getPapCodes($fiscal_year,$resp_center){
        $pap = RecommendedBudget::query()
            ->where('fiscal_year','=',$fiscal_year)
            ->where('resp_center','=',$resp_center)
            ->get();
        if(!empty($pap)){
            return $pap;
        }
    }

    public static function getPapCodesArray($fiscal_year,$resp_center,$group = false){
        $paps = self::getPapCodes($fiscal_year,$resp_center);
        $pap_codes_array = [];
        if(!empty($paps)){
            foreach ($paps as $pap) {
                if($group == true){
                    if($pap->division == ''){
                        $pap->division = 'Others';
                    }
                    $pap_codes_array[$pap->division][$pap->pap_code] = $pap->pap_code.' - '.$pap->pap_title;
                }else{
                    $pap_codes_array[$pap->pap_code] = $pap->pap_code.' - '.$pap->pap_title;
                }
            }
        }
        return $pap_codes_array;
    }

    public static function modesOfProcurement(){
        return [
            'smallValueProcurement' => 'SMALL VALUE PROCUREMENT',
            'bidding' => 'BIDDING',
            'repeatOrder' => 'REPEAT ORDER',
            'shopping' => 'SHOPPING',
            'directContracting' => 'DIRECT CONTRACTING',
        ];
    }

    public static function unitsOfMeasurementPPMP(){
        return [
            'pc' => 'PC',
            'unit' => 'UNIT',
        ];
    }

    public static function milestones(){
        return ['Jan', 'Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    }

    public static function camelCaseToWords($word){
        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';

        return preg_replace($pattern, $replace, $word);
    }

    public static function sqlServerIsOn(){
        $setting = SuSettings::query()->where('setting','=','sql_server')->first();
        if(!empty($setting)){
            if($setting->int_value === 1){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function dtrMenuOn(){
        $setting = SuSettings::query()->where('setting','=','dtr_menu')->first();
        if(!empty($setting)){
            if($setting->int_value === 1){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function departmentsArray(){
        $depts = Department::query()->orderBy('department_id','asc')->get();
        $deptsArr = [];
        if(!empty($depts)){
            foreach ($depts as $dept){
                $deptsArr[$dept->department_id] = $dept->name;
            }
        }
        return $deptsArr;
    }

    public static function deviceInfo(){
        $dev = collect();
        $user_agent = request()->header('User-Agent');
        $bname = 'Unknown';
        $platform = 'Unknown';

        //First get the platform?
        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }else{
            $platform = 'not detected';
        }

//        echo $platform;

        $dev->platform = strtoupper($platform);


        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$user_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$user_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$user_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$user_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }else{
            $bname = 'Not detected';
        }
        $dev->browser = ucfirst($bname);

        return $dev;
    }

    public static function educationalLevels(){
        return [
            'ELEMENTARY' => 'ELEMENTARY',
            'SECONDARY' => 'SECONDARY',
            'VOCATIONAL/TRADE COURSE' => 'VOCATIONAL/TRADE COURSE',
            'COLLEGE' => 'COLLEGE',
            'GRADUATE STUDIES' => 'GRADUATE STUDIES',
        ];
    }


    public static function populateOptionsFromObject($object,$option_key,$value_key,$selected = null){
        $opt = '';
        $array = $object->toArray();
        if(count($array) > 0){
            foreach ($array as $key => $item){
                if($item[$value_key] == $selected){
                    $s = 'selected';
                }else{
                    $s = '';
                }
                $opt = $opt.'<option value="'.$item[$value_key].'" '.$s.'>'.$item[$option_key].'</option>';
            }
        }else{

        }
        return $opt;
    }

    public static function populateOptionsFromObjectAsArray($object,$option_key,$value_key,$selected = null){
        $opt = '';
        $array = $object->toArray();
        $new_arr = [];
        if(count($array) > 0){
            foreach ($array as $key => $item){
                $new_arr[$item[$option_key]] = $item[$value_key];
//                array_push($new_arr,[]);
            }
        }else{

        }
        return $new_arr;
    }

    public static function populateOptionsFromArray($array,$selected = null){
        $opt = '';
        if(count($array) > 0){
            foreach ($array as $key => $value){
                if($key == $selected){
                    $s = 'selected';
                }else{
                    $s = '';
                }
                $opt = $opt.'<option value="'.$key.'" '.$s.'>'.$value.'</option>';
            }
        }
        return $opt;
    }

    public static function convertFromBytes($byte,$to){
        $to = strtoupper($to);
        $final = null;
        switch ($to){
            case 'KB':
                $final = $byte/1000;
                break;
            case 'MB':
                $final = $byte/1000000;
                break;
            case 'GB':
                $final = $byte/1000000000;
                break;
            case 'TB':
                $final = $byte/1000000000000;
                break;
        }
        return $final;
    }

    public static function checkRouteAccess($routeName){
        $a = \App\Models\UserSubmenu::query()->where('user_id','=',\Illuminate\Support\Facades\Auth::user()->user_id)
            ->leftJoin('su_submenus','su_submenus.submenu_id','=','su_user_submenus.submenu_id')
            ->where('su_submenus.route','=',$routeName)
            ->first();
        return $a;
    }
    public static function wrapForSelect2($array,$paginate = true){
        return [
            'results' => $array,
            "pagination" => [
                "more" => $paginate ,
            ]
        ];
    }

    public static function toNumber($number,$places = 2,$placeholder = ''){
        if($number == null || $number == 0){
            return $placeholder;
        }else{
            return number_format($number,$places);
        }
    }

    public static function quarters(){
        return [
            1 => ['01' => null,'02' => null,'03' => null],
            2 => ['04' => null,'05' => null,'06' => null],
            3 => ['07' => null,'08' => null,'09' => null],
            4 => ['10' => null,'11' => null,'12' => null],
        ];
    }

    public static function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public static function ordinalWords(){
        $arr = [
            1 => 'FIRST',
            2 => 'SECOND',
            3 => 'THIRD',
            4 => 'FOURTH',
        ];
        return $arr;
    }

    public static function dateFormat($date,$format = 'M. d, Y'){
        if($date == null) {
            return null;
        }else{
            return Carbon::parse($date)->format($format);
        }
    }

    public static function listingToDatalist($datalistId, $listing){
        $html = '<datalist id="'.$datalistId.'">';

        foreach ($listing as $data){
            $html .= '<option value="'.$data.'"></option>';
        }
        $html .= '</datalist>';
        return  $html;
    }

    public static function ksortRecursive(&$array, $sort_flags = SORT_REGULAR) {
        if (!is_array($array)) return false;
        ksort($array, $sort_flags);
        foreach ($array as &$arr) {
            self::ksortRecursive($arr, $sort_flags);
        }
        return true;
    }

    public static function flattenArray($array){
        $arr = [];
        foreach ($array as $a){
            $arr[$a] = $a;
        }
        return $arr;
    }

    public static function middleInitial($text,$append = '.'){
        if($text != null){
            return Str::limit($text,1,$append);
        }
        return '';
    }

    public static function divide($num1 , $num2){
        try {
            if(@($num1 / $num2) === false) return INF; // covers PHP5
            return @($num1 / $num2); // covers PHP7
        } catch (\DivisionByZeroError $e) {
            return INF; // covers PHP8
        }
    }

    public static function months(){
        $year = Carbon::now()->format('Y');
        $months = [];
        for ($m = 1;$m <= 12; $m++){
            $start = Carbon::parse($year.'-'.str_pad($m,2,'0',STR_PAD_LEFT).'-01')->format('Y-m-d');
            $months[str_pad($m,2,'0',STR_PAD_LEFT)] = [
                'start' => $start,
                'end' => \Illuminate\Support\Carbon::parse($start)->lastOfMonth()->format('Y-m-d'),
            ];
        }
        return $months;

    }
}