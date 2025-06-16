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
        Schema::create('uniform_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_record_id')->constrained()->cascadeOnDelete(); // Ties to existing inventory
            $table->string('type'); // e.g. Polo, Pants
            $table->string('size'); // e.g. S, M, L, XL
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uniform_inventories');
    }
};
