<?php

namespace App\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class WorkingRangeBuilder extends Builder
{

  public function validated(): self
  {
    return $this->whereNotNull('validated_at');
  }

  public function unvalidated(): self
  {
    return $this->whereNull('validated_at');
  }
}
