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
        Schema::create('affectation_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('emploie_id');
            $table->unsignedBigInteger('vehicule_id');
            $table->dateTime('date_affectation');
            $table->string('status');
            $table->text('commentaire_emploie')->nullable();
            $table->boolean('isDeleted')->default(false);
            $table->timestamps();

            // Clés étrangères (à adapter si les tables existent déjà)
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('emploie_id')->references('id')->on('emploies')->onDelete('cascade');
            $table->foreign('vehicule_id')->references('id')->on('vehicules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affectation_commandes');
    }
};
