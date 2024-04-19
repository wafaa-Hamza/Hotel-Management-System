<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HandelDateToHeugry{
  
  public function gregorianToHijri($gregorianDate)
{
    $timestamp = strtotime($gregorianDate);
    $jd = cal_to_jd(CAL_GREGORIAN, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp));

    // Calculate Hijri date
    $hijri = $this->jd_to_hijri($jd);

    return implode('-', $hijri);
}

public function jd_to_hijri($jd)
{
    $l = $jd - 1948440 + 10632;
    $n = floor(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;

    $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
    $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
    $m = floor((24 * $l) / 709);
    $d = $l - floor((709 * $m) / 24);
    $y = 30 * $n + $j - 30;
    return [$y, $m, $d];
}


}

