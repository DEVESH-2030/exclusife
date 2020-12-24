<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('create_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile_no');
            $table->integer('product_inquired_id')->unsigned()->nullable();
            $table->float('customer_price_expectation')->unsigned()->nullable();
            $table->date('expected_date');
            $table->text('request_text');
            $table->string('image_file')->nullable();
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
        Schema::dropIfExists('create_requests');
    }
}
