<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Toplog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('top_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip');
            # $table->json('top');
            $table->longText('top');
            $table->decimal('cpu_usage');
            $table->decimal('mem_usage');
            $table->decimal('swap_usage');
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
        Schema::dropIfExists('top_logs');
        Schema::dropIfExists('ips');
    }
}
