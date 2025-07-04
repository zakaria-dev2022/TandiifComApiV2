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
        // Schema::create('client', function (Blueprint $table) {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); 
            $table->string('nom_complet');
            // $table->string('cin')->unique();
            $table->string('tel');
            $table->string('email')->unique();
            // $table->string('adresse');
            $table->string('profil')->nullable(); // Peut contenir un lien vers une image
            $table->boolean('isDeleted')->default(false);
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
        Schema::dropIfExists('clients');
    }
};
