<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->increments('id');
            //quando não for trabalhar com timestamps preciso ir no modelo e declarar
            //$table->timestamps();
            $table->unsignedInteger('user_id')->unsigned();
            //chave estrangeira q tem como referencia o id da tabela users q será deletado em cascata
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
