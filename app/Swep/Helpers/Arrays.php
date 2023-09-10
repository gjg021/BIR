<?php


namespace App\Swep\Helpers;


use App\Models\Applicant;
use App\Models\ApplicantPositionApplied;
use App\Models\Budget\ChartOfAccounts;
use App\Models\HRPayPlanitilla;
use App\Models\MDDC;
use App\Models\PPU\PPURespCodes;
use App\Models\PPU\RCDesc;
use App\Models\SuOptions;
use Auth;
use Illuminate\Support\Carbon;

class Arrays
{
    public static function accessToDocuments(){
        return [
            'VIS' => 'VIS',
            'QC' => 'QC',
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
            'SINGLE' => 'SINGLE',
            'MARRIED' => 'MARRIED',
            'WIDOWED' => 'WIDOWED',
            'DIVORCED' => 'DIVORCED',
            'SEPARATED' => 'SEPARATED',
        ];
    }

    public static function accessToEmployees(){
        return [
            'VIS' => 'VIS',
            'LM' => 'LM',
            'QC' => 'QC',
            'LGAREC' => 'LGAREC',
        ];
    }

    public static function payPlantillas(){
        $array = ['1'=>2];
        $pps = HRPayPlanitilla::query()->select('item_no','position')->get();
        if(!empty($pps)){
            foreach ($pps as $pp){
                $array[$pp->item_no] = $pp->position;
            }
        }
        return $array;
    }

    public static function payPlantillasWithItemNumber(){

        $pps = HRPayPlanitilla::query()->select('item_no','position')->get();
        return $pps->pluck('position','item_no')->map(function ($value,$key){
            return $key.' - '.$value;
        })->toArray();
    }

    public static function portals(){
        return [
            'DIGIFILE' => 'DIGIFILE',
            'PPU' => 'PPU',
            'MIS' => 'MIS',
        ];
    }
    public static function countries(){
        return [
            "AF" => "Afghanistan",
            "AX" => "Aland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BQ" => "Bonaire, Sint Eustatius and Saba",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, Democratic Republic of the Congo",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CW" => "Curacao",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "XK" => "Kosovo",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthelemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SX" => "Sint Maarten",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        ];
    }

    public static function fonts(){
        $a = [
            'Arial' => 'Arial',
            'Cambria'=> 'Cambria',
            'Calibri' => 'Calibri (default)',
            'Verdana' => 'Verdana',
            'Futura' => 'Futura',
            'Times New Roman' => 'Times New Roman',
            'Garamond' => 'Garamond',
            'Rockwell' => 'Rockwell',
            'Franklin Gothic'=> 'Franklin Gothic',

        ];
        ksort($a);
        return $a;
    }
    public static function fontSizes(){
        return [
            '8px' => '8px',
            '9px' => '9px',
            '10px' => '10px',
            '11px' => '11px',
            '12px' => '12px',
            '13px' => '13px',
            '14px' => '14px',
            '15px' => '15px',
            '16px' => '16px',
            '17px' => '17px',
        ];
    }

    public static function positionsAppliedFor(){
        $arr = [];
        $positions = ApplicantPositionApplied::query()->groupBy('position_applied')->orderBy('position_applied','asc')->get();
        foreach ($positions as $position){
            $arr[$position->position_applied] = $position->position_applied;
        }

        return $arr;
    }

    public static function orsFunds(){
        return [
            '01' => '01 - Personnel Services',
            '02' => '02 - MOOE',
            '04' => '04',
            '06' => '06 - Special Projects',
            '12' => '12',
            '20' => '20',
            '69' => '69',
        ];
    }

    public static function orsBooks(){
        return [
            'TEV' => 'TEV',
            'PAY' => 'PAY',
            'DV' => 'DV',
            'PO' => 'PO',
            'JO' => 'JO',
        ];
    }

    public static function oldOrsBooks(){
        $arr =[
            3 => 'TEV',
            0 => 'DV',
            1 => 'PO',
            2 => 'JO',
            'PAY' => 'PAY',
        ];
        ksort($arr);

        return $arr;
    }


    public static function groupedRespCodes($all = null){

        $rcs = PPURespCodes::query()->with(['description']);
        if($all == 'vis'){
            $rcs->where('vis','=',1);
        }
        $rcs =  $rcs->get();
        $arr = [];

        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->description->name][$rc->rc_code] = $rc->desc;
            }
        }
        return $arr;
    }

    public static function years($past = 8, $future = 10){
        $years = [];
        $now_year = Carbon::now()->format('Y');
        for ( $x = $now_year - $past ; $x <= $now_year + $future; $x++){
            $years[$x] = $x;
        }
        return $years;
    }

    public static function respCodeList(){
        $rcs = PPURespCodes::query()->get();
        $arr = [];
        foreach ($rcs as $rc){
            $arr[$rc->rc_code] = [
                'dept_alias' => $rc->description->name,
                'dept' => $rc->department,
                'div' => $rc->division,
                'sec' => $rc->section,
            ];
        }
        return $arr;
    }

    public static function departmentList(){
        $depts = RCDesc::query()->get();
        $arr = [];
        if(count($depts) > 0){
            foreach ($depts as $dept) {
                $arr[$dept->rc] = $dept->name.' | '.$dept->descriptive_name ;
            }
        }
        return $arr;
    }

    public static function departmentListAbbv(){
        $depts = RCDesc::query()->get();
        $arr = [];
        if(count($depts) > 0){
            foreach ($depts as $dept) {
                $arr[$dept->rc] = $dept->name;
            }
        }
        return $arr;
    }

    public static function quartersArray(){
        $arr = [
            1 => 'FIRST',
            2 => 'SECOND',
            3 => 'THIRD',
            4 => 'FOURTH',
        ];
        return $arr;
    }

    public static function fundSources(){
        return [
            'COB' => 'COB',
            'SIDA' => 'SIDA',
        ];
    }

    public static function papTypes(){
        $arr = [];
        $ops = SuOptions::query()->where('for','=','papTypes')->get();
        if(!empty($ops)){
            foreach ($ops as $op){
                $arr[$op->option] = $op->value;
            }
        }
        ksort($arr);
        return $arr;
    }
    public static function activeInactive(){
        return[
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    public static function deptsAssoc(){
        $depts = RCDesc::query()->get();
        $arr = [];
        foreach ($depts as $dept){
            $arr[$dept->name] = null;
        }
        return $arr;
    }

    public static function chartOfAccounts(){
        $arr = [];
        $coas = ChartOfAccounts::query()->select('account_code','account_title')->get();
        if(!$coas->isEmpty()){
            return $coas
                ->pluck('account_title','account_code')
                ->map(function ($value,$key){
                    return $key.' | '.$value;
                })
                ->sort()
                ->toArray();
        }
        return null;
    }

    public static function employeeAssignments(){
        $mddc = MDDC::query()
            ->get();
        return [
            'OFFICE-BASED' => [
                'BACOLOD OFFICE' => 'BACOLOD OFFICE',
                'QUEZON CITY OFFICE' => 'QUEZON CITY OFFICE',
            ],
            'FIELD' => $mddc->pluck('slug','slug')->sort()->toArray(),
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

    public static function educationalLevelsLimited(){
        return [
            'VOCATIONAL/TRADE COURSE' => 'VOCATIONAL/TRADE COURSE',
            'COLLEGE' => 'COLLEGE',
            'GRADUATE STUDIES' => 'GRADUATE STUDIES',
        ];
    }

    public static function suppliesClassification(){
        return [
            'IT SUPPLIES' => 'IT SUPPLIES',
            'OFFICE SUPPLIES' => 'OFFICE SUPPLIES',
            'JANITORIAL SUPPLIES' => 'JANITORIAL SUPPLIES',
            'PROTECTIVE SUPPLIES' => 'PROTECTIVE SUPPLIES',
            'CONSTRUCTION SUPPLIES' => 'CONSTRUCTION SUPPLIES',
            'SPECIAL REQUEST' => 'SPECIAL REQUEST',
            'ELECTRICAL SUPPLIES' => 'ELECTRICAL SUPPLIES',
        ];
    }
}