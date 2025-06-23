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
            $table->foreignId('uniform_inventory_id')->constrained()->cascadeOnDelete();
            $table->integer('student_id')->nullable(); // Nullable for staff distributions
            $table->string('student_name')->nullable(); // Nullable for staff distributions
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->nullable(); // Nullable for staff distributions
            $table->unsignedInteger('quantity')->default(1);
            $table->text('remarks')->nullable();
            $table->timestamps();
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
