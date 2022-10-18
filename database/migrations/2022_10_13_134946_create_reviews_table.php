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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('user_id')->nullable();
            $table->text('text');
            $table->string('benefits')->nullable();
            $table->string('disadvantages')->nullable();
            $table->integer('rating');
            $table->boolean('bought');
            $table->foreign('user_id')
                ->on('users')->references('id')->onDelete('SET NULL');
            $table->foreign('product_id')
                ->on('products')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('reviews');
    }
};
