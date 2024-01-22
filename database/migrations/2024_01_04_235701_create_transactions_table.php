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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('sending_account_id');
            $table->string('sending_user_id');
            $table->string('sending_account_currency');
            $table->string('receiving_account_id');
            $table->string('receiving_user_id');
            $table->string('receiving_account_currency');
            $table->integer('money_in_lcm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
