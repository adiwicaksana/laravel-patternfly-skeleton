<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreMdmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_mdm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_code');
            $table->string('initials_code')->nullable();
            $table->string('store_desc')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('store_status');

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
        Schema::dropIfExists('store_mdm');
    }
}
