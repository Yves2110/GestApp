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
        Schema::create('activity_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained();
            $table->integer('number_of_participants');
            $table->integer('number_of_trainer');
            $table->integer('number_of_days');
            $table->string('place');
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
        Schema::dropIfExists('activity-variables');
    }
};
