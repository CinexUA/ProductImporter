<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('vendor_code', 15)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->tinyInteger('guarantee')->default(0);
            $table->tinyInteger('availability')->default(0);
            $table->timestamps();

            $table->foreignId('category_id')->constrained();
            $table->foreignId('manufacturer_id')->constrained();
        });
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
}
