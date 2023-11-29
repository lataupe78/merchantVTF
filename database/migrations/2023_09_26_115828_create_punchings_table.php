<?php

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
        Schema::create('punchings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'worker_id')->nullable();

            $table->string('ip')->nullable();
            $table->string('status', 8)->nullable();

            $table->text('device_infos')->nullable();
            
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->decimal('altitude', 11, 8)->nullable();
            $table->decimal('accuracy', 11, 8)->nullable();
            $table->decimal('altitude_accuracy', 11, 8)->nullable();
            $table->date('current_date')->nullable();
            $table->timestamp('punched_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clockings');
    }
};
