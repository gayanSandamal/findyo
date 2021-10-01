<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rating', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userpost_rating_id');
            $table->foreign('userpost_rating_id')->references('id')->on('userpost_rating');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('adminrating');
            $table->decimal('rating', 5, 3)->default(0);
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
        Schema::dropIfExists('user_rating');
    }
}
