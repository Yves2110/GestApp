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
            $table->string('indicator')->nullable();
            $table->string('target')->nullable();
            $table->integer('price')->nullable();
            $table->string('source_of_funding')->nullable();
            $table->string('structure')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('commentary')->nullable();
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
