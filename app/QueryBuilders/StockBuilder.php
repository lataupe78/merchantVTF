<?php

namespace App\QueryBuilders;

use App\Models\SalePoint;
use Illuminate\Database\Eloquent\Builder;

class StockBuilder extends Builder
{
  public function forSalePoint(?SalePoint $salePoint = null): self
  {
    if ($salePoint) {
      return $this->where('sale_point_id', $salePoint->id);
    }
    return $this;
  }

  public function withAssets(): self
  {
    return $this->with('asset:id,name,asset_unit_id', 'asset.unit:id,abbr');
  }
}
