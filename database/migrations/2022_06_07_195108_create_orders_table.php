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
            $table->string('fullname', 200);
            $table->string('address', 200);
            $table->string('email', 200);
            $table->text('note')->nullable();
            $table->enum('checkout_method', ['onl', 'off']);
            $table->text('orders');
            $table->unsignedBigInteger('bill');
            $table->enum('status', ['confirming', 'solving', 'completing', 'deleting']);
            $table->string('checkConfirm', 100);
            $table->string('code', 100);
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
