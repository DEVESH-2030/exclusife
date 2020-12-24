<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->integer('code');
            $table->string('name');
            $table->integer('category_id')->unsigned();
            $table->integer('exempted_id')->unsigned();
            $table->integer('tax_type_id')->unsigned();
            $table->decimal('tax_percent', 5, 2);
            $table->decimal('cess_percent', 5, 2);
            $table->integer('quantity');
            $table->double('purchase_cost', 5, 2);
            $table->double('mrp', 5, 2);
            $table->integer('hsn_code');
            $table->string('uom');
            $table->string('description');
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
        Schema::dropIfExists('products');
    }
}
