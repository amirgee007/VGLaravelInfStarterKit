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
            $table->string('rank', 191);
            $table->string('thumbnail', 191);
            $table->text('url');
            $table->string('original_title', 250);
            $table->text('original_description');
            $table->string('price',20);
            $table->string('english_title',191);
            $table->text('english_description');
            $table->string('chinese_title',191)->nullable();
            $table->text('chinese_description')->nullable();
            $table->string('image',191)->nullable();
            $table->text('extra_images')->nullable();

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
        Schema::dropIfExists('potential_products');
    }
}
