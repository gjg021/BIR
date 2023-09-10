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
        Schema::create('bir_stock_entry', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->date('date')->nullable();
            $table->string('po_no')->nullable();
            $table->string('supplier')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });

        Schema::create('bir_stock_entry_details', function (Blueprint $table) {
            $table->id();
            $table->string('stock_entry_slug')->nullable();
            $table->string('slug')->nullable();
            $table->string('stock_no')->nullable();
            $table->string('article')->nullable();
            $table->string('article_name')->nullable();
            $table->string('description')->nullable();
            $table->string('uom')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('unit_cost',20,2)->nullable();
            $table->decimal('amount',20,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bir_stock_entry');
        Schema::dropIfExists('bir_stock_entry_details');
    }
};
