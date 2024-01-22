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
        Schema::create('checking_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number',24)->unique();
            $table->bigInteger('balance_in_lcm')->default(0);
            $table->string('currency_iso',3)->default('USD');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checking_accounts');
    }
};
