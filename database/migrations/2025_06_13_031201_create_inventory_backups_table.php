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
        Schema::create('inventory_backups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('qty');
            $table->string('unit');
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable();
            $table->foreignId('model_id')->nullable();
            $table->string('serial_number');
            $table->string('remarks')->nullable();
            $table->string('status');
            $table->foreignId('category_id');
            $table->foreignId('department_id');
            $table->foreignId('location_id');
            $table->foreignId('supplier_id');
            $table->timestamp('recorded_at')->useCurrent(); // use this instead of created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_backups');
    }
};
