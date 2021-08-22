<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('posts', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('post_category');
                $table->unsignedBigInteger('location_id')->nullable();
                $table->foreign('location_id')->references('id')->on('locations');
                $table->string('post_content',1000)->nullable();
                $table->enum('post_type', ['consumer', 'seller']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_category_id_foreign');
            $table->dropForeign('posts_location_id_foreign');
            $table->dropColumn(['category_id']);
            $table->dropColumn(['location_id']);
            $table->dropColumn(['post_content']);
            $table->dropColumn(['post_type']);
        });
    }
}
