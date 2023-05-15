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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes');
            $table->integer('subscriptions');
            $table->integer('monthly_searches');
            $table->integer('views');
            $table->integer('videos_count');
            $table->integer('premium_videos_count');
            $table->integer('white_label_video_count');
            $table->integer('rank');
            $table->integer('rank_premium');
            $table->integer('rank_wl');
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
        Schema::dropIfExists('stats');
    }
};
