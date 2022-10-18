<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('in_stock')->default(0);
            $table->integer('price');
            $table->bigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->on('categories')
                ->references('id')
                ->onDelete('SET NULL');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE products ADD CONSTRAINT chk_in_stock CHECK (in_stock >= 0);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
