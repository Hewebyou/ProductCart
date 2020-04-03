<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsCartTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('items_cart', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_id')->unsigned()->index()->nullable();
             $table->bigInteger('witchlist_id')->unsigned()->index()->nullable();
            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->decimal('quantity');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cart_items');
    }

}
