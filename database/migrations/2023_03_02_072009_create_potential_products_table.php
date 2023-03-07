<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotentialProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rank');
            $table->string('thumbnail');
            $table->text('url');
            $table->string('original_title', 250);
            $table->text('original_description');
            $table->string('price', 20);
            $table->string('english_title');
            $table->string('english_description');
            $table->string('chinese_title');
            $table->string('chinese_description');
            $table->string('image');
            $table->text('extra_images');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potential_products');
    }
}
