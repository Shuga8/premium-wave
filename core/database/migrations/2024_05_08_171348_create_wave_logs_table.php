<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wave_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 28, 8);
            $table->decimal('stop_loss', 28, 8);
            $table->decimal('take_profit', 28, 8);
            $table->decimal('pips', 28, 8);
            $table->decimal('price_was', 28, 8)->nullable();
            $table->decimal('price_is', 28, 8)->nullable();
            $table->string("wallet")->default("USD");
            $table->boolean("isForex")->default(false);
            $table->boolean("isCrypto")->default(false);
            $table->boolean("isCommodity")->default(false);
            $table->boolean("isStock")->default(false);
            $table->string("currency")->nullable();
            $table->string("crypto")->nullable();
            $table->string("commodity")->nullable();
            $table->string("stock")->nullable();
            $table->date('open_at')->nullable();
            $table->enum("status", ['pending', 'running', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wave_logs');
    }
};
