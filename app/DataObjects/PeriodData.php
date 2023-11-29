<?php

namespace App\DataObjects;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class PeriodData
{
  public string $type;
  public CarbonImmutable $current;
  public CarbonImmutable $prev;
  public CarbonImmutable $next;
  public ?CarbonImmutable $date_start;
  public ?CarbonImmutable $date_end;

  public array $formatted_dates;


  public function __construct(
    $type = 'month',
    $current,
    $prev = null,
    $next = null,
    $date_start = null,
    $date_end = null,
  ) {

    $this->type = $type;
    $this->current = $current;
    $this->prev = $prev;
    $this->next = $next;
    $this->date_start = $date_start;
    $this->date_end = $date_end;

    $this->formatted_dates =  $this->getFormattedDates();
  }


  public function getFormattedDates()
  {

    switch ($this->type) {

      case 'month': {
          return [
            'current' => $this->current->format('Y-m-d'),
            'date_start' => $this->date_start->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
            'prev' => $this->prev,
            'next' => $this->next,
          ];
          break;
        }
      case 'week': {
          return [
            'current' => $this->current->format('Y-m-d'),
            'date_start' => $this->date_start->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
            'prev' => $this->prev,
            'next' => $this->next,
          ];
        }
      case 'day': {
          return [
            'current' => $this->current->format('Y-m-d'),
            'date_start' => null, //$this->date_start->format('Y-m-d'),
            'date_end' => null, //$this->date_end->format('Y-m-d'),
            'prev' => $this->prev,
            'next' => $this->next,
          ];
        }
    }
  }
}
