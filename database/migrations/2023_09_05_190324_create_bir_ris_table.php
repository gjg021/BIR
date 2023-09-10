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
        Schema::create('bir_ris', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('division')->nullable();
            $table->string('fund_cluster')->nullable();
            $table->string('rcc')->nullable();
            $table->string('office')->nullable();
            $table->string('ris_no')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });

        Schema::create('bir_ris_details',function (Blueprint $table){
            $table->id();
            $table->string('ris_slug')->nullable();
            $table->string('slug')->nullable();
            $table->string('stock_no')->nullable();
            $table->string('uom')->nullable();
            $table->string('article')->nullable();
            $table->string('article_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty_requested')->nullable();
            $table->boolean('is_available')->nullable();
            $table->integer('qty_issued')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bir_ris');
        Schema::dropIfExists('bir_ris_details');
    }
};
