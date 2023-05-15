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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pornstar_id')->constrained('pornstars');
            $table->string('hair_color')->nullable();
            $table->string('ethnicity')->nullable();
            $table->boolean('tattoos');
            $table->boolean('piercings');
            $table->integer('breast_size')->nullable();
            $table->string('breast_type')->nullable();
            $table->string('gender');
            $table->string('orientation');
            $table->integer('age')->nullable();
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
        Schema::dropIfExists('attributes');
    }
};
