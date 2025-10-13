<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->integer('montant_demande')->nullable();
            $table->string('motif_permi')->nullable();
            $table->string('motif')->nullable();
            $table->string('detail')->nullable();
            $table->string('payement')->nullable();
            $table->string('lieu_travail')->nullable();
            $table->string('heure_debut')->nullable();
            $table->string('heure_fin')->nullable();
            $table->date('date_depart')->nullable();
            $table->date('date_fin')->nullable();
            $table->string('type')->nullable();
            $table->json('procces_valide_result')->nullable();
            $table->integer('statut')->default('0');
            $table->integer('nombre_de_jours')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('direction_id')->nullable();
            $table->unsignedBigInteger('filliale_id')->nullable();
            $table->unsignedBigInteger('type_demandes_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
