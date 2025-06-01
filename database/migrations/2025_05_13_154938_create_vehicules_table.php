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
        Schema::create('vehicules', function (Blueprint $table) {
             $table->id();
            $table->string('marque');
            $table->unsignedBigInteger('type_id');
            $table->string('matricule');
            $table->string('status');
            $table->string('image')->nullable();
            $table->boolean('isDeleted')->default(false);
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('type_vehicules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicules');
    }
};
