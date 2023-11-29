<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CustomDatetime implements CastsAttributes
{
  public function get($model, $key, $value, $attributes)
  {
    if ($value == null) {
      return null;
    }

    // $value is the original timestamp value
    return [
      'date' => date('Y-m-d', strtotime($value)),
      'time' => date('H:i', strtotime($value)),
      'val' => $value,
    ];
  }

  public function set($model, $key, $value, $attributes)
  {
    // $value is an array with 'date' 'time' and 'val' keys
    if (is_array($value) && isset($value['date'], $value['time'])) {
      $date = $value['date'];
      $time = $value['time'];
      // Combine date and time and return as a timestamp
      return $date . ' ' . $time . ':00';
    }

    // if (is_array($value) && isset($model->current_date, $value['time'])) {
    //   $date = $model->current_date;
    //   $time = $value['time'];
    //   // Combine date and time and return as a timestamp
    //   return $date . ' ' . $time . ':00';
    // }

    // unset($value['date'], $value['time'], $value['val']);

    // return $value['val'];
    return $value;
  }
}
