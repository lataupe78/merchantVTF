<?php

use App\Models\SalePoint;
use App\Models\User;
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
        Schema::create('working_ranges', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();
            $table->string('title')->nullable();
            
            $table->date('current_date')->nullable();

            $table->foreignIdFor(User::class, 'worker_id')
                ->nullable()
                ->constrained();

            $table->foreignIdFor(SalePoint::class, 'sale_point_id')
                ->nullable()
                ->constrained();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->string('comment')->nullable();

            // employee range
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            
            // employee range validated
            $table->timestamp('validated_started_at')->nullable();
            $table->timestamp('validated_ended_at')->nullable();
     
      
            // $table->timestamp('validator_id')->nullable();
            $table->timestamp('validated_at')->nullable();
      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_ranges');
    }
};
