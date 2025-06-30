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
        Schema::create('uniform_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_identification_id')->constrained()->cascadeOnDelete();// Nullable for staff distributions
            $table->string('receipt_number')->nullable(); // Nullable for staff distributions
            $table->json('sizes_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uniform_distributions');
    }
};
