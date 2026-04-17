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
        Schema::table('customer', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('type_of_service', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('trans_order', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('level', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('type_of_service', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('trans_order', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('level', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
