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
            $table->string('label'); // Nom & prénom
            $table->date('date');    // Date de la demande
            $table->string('lieu');
            $table->date('du');
            $table->date('au');
            $table->string('motif');
            $table->decimal('montant_c', 10, 2); // Montant demandé (chiffre)
            $table->string('en_lettre');         // Montant en lettres
            $table->decimal('billet', 10, 2);
            $table->decimal('cheque', 10, 2);
            $table->decimal('hebergement', 10, 2);
            $table->decimal('espece', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('statut')->default('0');
            $table->foreignId('users_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('direction_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('filliale_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('type_demandes_id')->contrained()->onDelect('cascade')->nullable();
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
