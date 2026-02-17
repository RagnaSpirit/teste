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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('document_type', 10)->nullable()->after('tin');
            $table->string('cpf_rg_front_image')->nullable()->after('tin_certificate_image');
            $table->string('cpf_rg_back_image')->nullable()->after('cpf_rg_front_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'cpf_rg_front_image', 'cpf_rg_back_image']);
        });
    }
};
