<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  AlterPostImagersTableAddPostId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_imagers', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable();;
            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_imagers', function (Blueprint $table) {
            $table->dropForeign('post_imagers_post_id_foreign');
            $table->dropColumn(['post_id']);
        });
    }
}
