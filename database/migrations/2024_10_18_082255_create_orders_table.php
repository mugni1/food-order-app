<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 100);
            $table->string('table_no', 10);
            $table->date('order_date');
            $table->time('order_time');
            $table->string('status', 100);
            $table->integer('total');
            $table->unsignedBigInteger('waitress_id');
            $table->unsignedBigInteger('cashier_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('waitress_id')->references('id')->on('users');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};