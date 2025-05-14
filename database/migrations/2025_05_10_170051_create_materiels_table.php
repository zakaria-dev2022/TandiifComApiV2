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
        Schema::create('materiels', function (Blueprint $table) {
           $table->id();
        $table->string('nom');
        $table->unsignedBigInteger('type_materiel_id');
        $table->string('description');
        $table->string('image');
        $table->integer('qte');
        $table->boolean('isDeleted')->default(false);
        $table->timestamps();

        $table->foreign('type_materiel_id')->references('id')->on('type_materiels')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materiels');
    }
};
