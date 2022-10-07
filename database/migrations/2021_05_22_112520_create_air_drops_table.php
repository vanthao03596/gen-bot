<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_drops', function (Blueprint $table) {
            $table->id();
            $table->string('tele_name')->nullable();
            $table->unsignedBigInteger('chat_id');
            $table->string('referral_code')->nullable();
            $table->string('address');
            $table->timestamps();

            $table->unique(['chat_id', 'referral_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('air_drops');
    }
}
