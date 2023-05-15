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
        Schema::create('pornstars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained('feeds');
            $table->string('name');
            $table->string('license');
            $table->string('wl_status');
            $table->string('link');
            $table->json('aliases')->nullable();
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
        Schema::dropIfExists('pornstars');
    }
};
