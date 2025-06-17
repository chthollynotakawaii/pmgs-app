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
        Schema::table('inventory_records', function (Blueprint $table) {
            $table->boolean('borrowed')->default(false)->after('status');
            $table->string('borrowed_location')->nullable()->after('borrowed');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_records', function (Blueprint $table) {
            $table->dropColumn(['borrowed', 'borrowed_location']);
        });
    }

};
