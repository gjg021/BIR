<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bir_office_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('classification')->nullable();
            $table->string('stock_no')->nullable();
            $table->string('article')->nullable();
            $table->string('description')->nullable();
            $table->string('uom')->nullable();
            $table->integer('reordering_point')->nullable();
            $table->integer('stock')->nullable();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
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
        Schema::dropIfExists('bir_office_supplies');
    }
};
