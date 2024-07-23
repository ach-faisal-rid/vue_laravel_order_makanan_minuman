<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('table_no', 2);
            $table->string('status', 100);
            $table->integer('total')->unsigned();
            $table->unsignedBigInteger('waitress_id');
            $table->unsignedBigInteger('chasier_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('waitress_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('chasier_id')->references('id')->on('roles')->onDelete('cascade');
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
