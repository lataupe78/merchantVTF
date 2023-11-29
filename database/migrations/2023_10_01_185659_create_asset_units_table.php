<?php

use App\Models\Asset;
use App\Models\AssetUnit;
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
        Schema::create('asset_units', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('plural')->nullable();

            $table->string('abbr')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('asset_asset_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Asset::class, 'asset_id')
                ->nullable()
                ->constrained();

            $table->foreignIdFor(AssetUnit::class, 'asset_unit_id')
                ->nullable()
                ->constrained();

            $table->unsignedInteger('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_asset_unit');
        Schema::dropIfExists('asset_units');
    }
};
