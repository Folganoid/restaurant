<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrderMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_menus', function (Blueprint $table) {
            //
            $table->integer('order_id')->unsigned()->default(1);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('menu_id')->unsigned()->default(1);
            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_menus', function (Blueprint $table) {
            //
        });
    }
}
