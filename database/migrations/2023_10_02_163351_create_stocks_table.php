<?php

use App\Models\Asset;
use App\Models\SalePoint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Asset::class, 'asset_id')->constrained();

            $table->foreignIdFor(SalePoint::class, 'sale_point_id')->nullable()->constrained();
            
            $table->unsignedBigInteger('current_stock')->nullable();

            $table->unsignedBigInteger('danger_level')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
