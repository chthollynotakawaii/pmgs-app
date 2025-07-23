<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('uniform_stock_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('uniform_type', 100);
            $table->string('size', 50);
            $table->unsignedInteger('total_quantity')->default(0);
            $table->timestamps();

            $table->unique(['course_id', 'uniform_type', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uniform_stock_summaries');
    }
};