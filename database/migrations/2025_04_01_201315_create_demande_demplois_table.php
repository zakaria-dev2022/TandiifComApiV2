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
        Schema::create('demandes_demplois', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet');
            $table->string('cin')->unique();
            $table->string('tel');
            $table->string('email')->unique();
            $table->string('copie_cin');
            $table->string('copie_permis')->nullable();
            $table->text('adresse')->nullable();
            $table->string('profil')->nullable(); // Photo de profil
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
        Schema::dropIfExists('demande_demplois');
    }
};
