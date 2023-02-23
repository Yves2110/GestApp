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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained();
            $table->foreignId('objective_id')->constrained();
            $table->foreignId('under_objective_id')->constrained();
            $table->foreignId('periode_id')->constrained();
            $table->string('label');
            $table->string('indicator');
            $table->string('target');
            $table->integer('price');
            $table->string('source_of_funding');
            $table->string('structure');
            $table->boolean('status')->default(0);
            $table->longText('commentary');
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
        Schema::dropIfExists('activities');
    }
};
