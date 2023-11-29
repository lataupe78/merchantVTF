<?php

use App\Models\User;
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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->integer('cash')->default(0);
            $table->integer('cash_reel')->nullable()->default(0);

            $table->integer('card')->default(0);
            $table->integer('card_reel')->nullable()->default(0);

            // should be a manager
            $table->foreignIdFor(User::class, 'author_id')->constrained()->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'seller_id')->nullable()->constrained()->cascadeOnDelete();

            $table->foreignIdFor(SalePoint::class, 'sale_point_id')->constrained();

            // $table->foreignIdFor(Team::class, 'team_id')->nullable()->constrained();

            $table->text('comment')->nullable();

            $table->boolean('is_validated')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
