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
        Schema::create('bracket_pathways', function (Blueprint $table) {
            $table->id();
            $table->string('bracket_type');
            $table->integer('matchup');
            $table->string('winning_path')->nullable();
            $table->string('losing_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bracket_pathways');
    }
};
