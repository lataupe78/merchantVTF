<?php

use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('stock');
            
            $table->foreignIdFor(Asset::class, 'asset_id')->constrained();
            $table->foreignIdFor(SalePoint::class, 'sale_point_id')->constrained();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained();


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
