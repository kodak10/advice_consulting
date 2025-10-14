<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nom_prenom'); // Nom & prénom
            $table->date('date');    // Date de la demande
            $table->string('lieu');
            $table->date('debut');
            $table->date('fin');
            $table->string('motif');
            $table->decimal('montant_en_chiffre', 10, 2); // Montant demandé (chiffre)
            $table->string('montant_en_lettre');         // Montant en lettres
            $table->decimal('billet_avion', 10, 2);
            $table->decimal('cheque', 10, 2);
            $table->decimal('hebergement_repars', 10, 2);
            $table->decimal('especes', 10, 2);
            $table->decimal('totale', 10, 2);
            $table->string('pdf_path')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
