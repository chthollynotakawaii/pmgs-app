<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrowing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity'); 
            $table->dateTime('returned_at')->nullable();
            $table->string('remarks')->nullable()->default('borrowed');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_logs');
    }
};
