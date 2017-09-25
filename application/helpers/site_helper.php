<?php
function next_code($tbl,$col="code"){
    $CI =& get_instance();
    $code = $CI->site_model->get_last_code($tbl,$col);
    if (preg_match('/^(\D*?)(\d+)(.*)/', $code, $result) == 1){
        list($all, $prefix, $number, $postfix) = $result;
        $dig_count = strlen($number); // How many digits? eg. 0003 = 4
        $fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
        $nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on
        $new_ref=$prefix.$nextval.$postfix;
    }
    else 
        $new_ref=$code;
    return $new_ref;
}
function site_alert($text="",$type="alert"){
    $CI =& get_instance();
    $site_alerts = array();
    if($CI->session->userdata('site_alerts')){
        $site_alerts = $CI->session->userdata('site_alerts');
    }

    $site_alerts[] = array("text"=>$text,"type"=>$type);
    $CI->session->set_userdata('site_alerts',$site_alerts);
}
function age($dob){
    if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    }else{
        return 0;
    }
}
function getPercent($num1,$num2){
    $percentage = 0;
    if($num1 > 0 && $num2 > 0)
        $percentage = ($num1/$num2)*100;
    return $percentage."%";
}
function strong($text){
    return "<strong>".$text."</strong>";
}
function small($text){
    return "<small>".$text."</small>";
}
function tagWord($text,$type='primary'){
    return "<span class='label label-".$type."'>".$text."</span>";
}
function fa($text){
    return "<i class='fa fa-fw ".$text."'></i>";
}
function sql2Date($date){
    return date('m/d/Y',strtotime($date));
}
function sql2DateTime($date){
	return date('m/d/Y H:i:s' ,strtotime($date));
}
function date2Sql($date){
   return date('Y-m-d', strtotime($date));
}
function date2SqlDateTime($date){
   return date('Y-m-d H:i:s', strtotime($date));
}
function time2Sql($date){
   return date('H:i:s', strtotime($date));
}
function sql2Time($date){
   return date('h:i a', strtotime($date));
}
function date2Word($date){
	return date('F d, Y', strtotime($date));
}
function date2Age($date){
    $birthDate = sql2Date($date);
    $birthDate = explode("/", $birthDate);
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    ? ((date("Y") - $birthDate[2]) - 1)
    : (date("Y") - $birthDate[2]));
    return $age;
}
function checkPhone($val,$dflt="+02"){
    if($val == "" || $val == $dflt){
        return null;
    }
    else
        return $val;
}
function blob2Image($blob){
    return 'data:image/jpeg;base64,'.base64_encode($blob);
}
function checkMobile($val,$dflt="+63"){
    if($val == "" || $val == $dflt){
        return null;
    }
    else
        return $val;
}

function iSetObj($obj,$name,$noneText=null){
    if(isset($obj->$name)){
        return $obj->$name;
    }
    else return $noneText;
}
function iSetObjDate($obj,$name,$noneText=null){
    if(isset($obj->$name)){
        if($obj->$name != "")
            return sql2Date($obj->$name);
    }
    else return $noneText;
}

function iSet($ar,$name,$noneText=null,$equal=null){
    if(isset($ar[$name])){
        return $ar[$name];
    }
    else return $noneText;
}

function ucFix($text=""){
    return ucwords(strtolower($text));
}
function dateRange($first, $last, $step = '+1 day', $format = 'm/d/Y' ) { 

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) { 

        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
    $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
    return $res;
}
function iEqual($value,$equal=0,$dfltTxtActive='Active',$dfltTxtInactive='Inactive'){
    if($value == $equal){
        return "<span class='text-success'>".$dfltTxtActive."</span>";
    }
    else return "<span class='text-danger'>".$dfltTxtInactive."</span>";
}

// function build_sorter($key) {
//     return function ($a, $b) use ($key) {
//         if($a['order']==$b['order']) return 0;
//         return $a['order'] < $b['order']?1:-1;
//     };
// }
function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

function getMonths($start,$end){
	$startDate = strtotime($start);
	$endDate   = strtotime($end);

	$currentDate = $startDate;

	while ($endDate >= $currentDate) {
		$months[] = date('M',$currentDate);
		$currentDate = strtotime( date('Y/m/01/',$currentDate).' +1 month');
	}
	return $months;
}

function got_pic_img($img){
        // echo var_dump(file_exists(base_url()."images/profile_pics/".$img));
    if(file_exists(base_url()."images/profile_pics/".$img)){
        // echo var_dump($img);
        // echo ""
        return "images/profile_pics/".$img;
    }
    else {
        return "images/profile_pics/default.jpg";
    }
}

function getDatesOfMonths($now=null){
    $num_of_days = date('t',strtotime($now));
    for( $i=1; $i<= $num_of_days; $i++)
     $dates[]= date('Y') . "-". date('m'). "-". str_pad($i,2,'0', STR_PAD_LEFT);
    // var_dump($dates);
    return $dates;
}

function getMonthYears($start,$end){
    $startDate = strtotime($start);
    $endDate   = strtotime($end);

    $currentDate = $startDate;

    while ($endDate >= $currentDate) {
        $months[] = date('M',$currentDate);
        $years[] = date('Y',$currentDate);
        $currentDate = strtotime( date('Y/m/01/',$currentDate).' +1 month');
    }

    return array("year"=>$years,"months"=>$months);
}

function num($num,$decimal=2,$decimal_point='.',$seperator=','){
    return number_format($num,$decimal,$decimal_point,$seperator);
}
function numInt($num,$decimal=2,$decimal_point='.',$seperator=''){
	return number_format($num,$decimal,$decimal_point,$seperator);
}
function numPercent($num,$decimal=0,$decimal_point='.',$seperator=','){
    return number_format($num,$decimal,$decimal_point,$seperator)."%";
}

function phpNow($format = "sql"){
	$today = getdate();
	if($format == "sql")
		$now = $today['year']."-".$today['mon']."-".$today['mday'];
	else{
		$now = $today['mon']."/".$today['mday']."/".$today['year'];
	}
	return $now;
}
function phpTime(){
    $today = getdate();
    $now = $today['hours']."-".$today['minutes']."-".$today['seconds'];

    return $now;
}

function vat($num=0){
    return ($num / 1.12);
}

function numSuffix($n) {
    $str = "$n";
    $t = $n > 9 ? substr($str,-2,1) : 0;
    $u = substr($str,-1);
    if ($t==1) return $str . 'th';
    else switch ($u) {
        case 1: return $str . 'st';
        case 2: return $str . 'nd';
        case 3: return $str . 'rd';
        default: return $str . 'th';
    }
}


function ago($time,$now)
{
   $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");
       $difference     = strtotime($now) - strtotime($time);
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {

       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
function identifyUserAgent()
{
    $user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
    $position   = stripos($user_agent, 'mobi');
    return ($position === false ? 'desktop' : 'mobile');
}

?>