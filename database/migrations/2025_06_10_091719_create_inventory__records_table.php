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
        Schema::create('inventory_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('qty');
            $table->string('unit');
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('model_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('serial_number')->unique();
            $table->string('temp_serial')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable()->index();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('borrowed')->default(false);
            $table->dateTime('recorded_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_records');
    }
};
