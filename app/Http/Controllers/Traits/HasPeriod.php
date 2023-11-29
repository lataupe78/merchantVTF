<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\DataObjects\PeriodData;

/**
 * THe purpose of this Trait is to reduce
 * the boilerplate around dates period operations
 * 
 */
trait HasPeriod
{
 
  private function datesForMonth($month = ''): PeriodData
  {
    try {
      $currentMonth = CarbonImmutable::createFromFormat('Y-m', $month)
        ->startOfMonth();
    } catch (\Exception $e) {
      // dump('error ' . $e->getMessage());
      $currentMonth = CarbonImmutable::now();
    }

    $date_start = $currentMonth->startOfMonth();
    $date_end = $currentMonth->endOfMonth();

    $prev = $currentMonth->copy()->sub('month', 1)->startOfMonth();
    $next = $currentMonth->copy()->add('month', 1)->startOfMonth();

    return new PeriodData('month', $currentMonth, $prev, $next, $date_start, $date_end,);
  }


  /**
   * params  week : format : yyyy-ww
   */
  private function datesForWeek($week = ''): PeriodData
  {
    try {

      list($year, $week) = explode('-', $week);

      $currentWeek = Carbon::now();
      $currentWeek->setISODate($year, $week)->startOfWeek();
    } catch (\Exception $e) {
      // dump('error ' . $e->getMessage());
      $currentWeek = Carbon::now()->startOfWeek();
      // dd('error ' . $e->getMessage());
    }

    $date_start = $currentWeek->copy()->startOfWeek();
    $date_end = $currentWeek->copy()->endOfWeek();

    $prev = $currentWeek->copy()->sub('week', 1)->startOfday();
    $next = $currentWeek->copy()->add('week', 1)->startOfday();

    return new PeriodData('week', $currentWeek, $prev, $next, $date_start, $date_end,);
  }



  private function datesForDay($day = ''): PeriodData
  {
    try {
      $currentDay = CarbonImmutable::createFromFormat('Y-m-d', $day)->startOfDay();
    } catch (\Exception $e) {
      $currentDay = CarbonImmutable::now()->startOfDay();
    }
    $prev = $currentDay->sub('day', 1)->startOfday();
    $next = $currentDay->add('day', 1)->startOfday();

    return new PeriodData('day', $currentDay, $prev, $next,);
  }
}
