<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('number');
            $table->unsignedDecimal('amount', 8, 2);
            $table->dateTime('payment_date')->nullable();
            $table->enum('payment_mode', ['cash', 'cheque', 'credit card', 'online'])->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->enum('status', ['created', 'paid', 'completed', 'canceled']);
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
        Schema::dropIfExists('orders');
    }
}
