<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAnnouncements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('announce_title')->nullable()->after('category_id');
            $table->enum('type',['Text', 'with Image', 'Gen Lead'])->nullable()->after('announce_title');
            $table->enum('only_customer',['For Whitelistng dues customer only'])->nullable()->after('type');
            $table->enum('only_whitelisted',['Only Whitelisted customer'])->nullable()->after('only_customer');
            $table->string('text_area')->nullable()->after('only_whitelisted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            //
        });
    }
}
