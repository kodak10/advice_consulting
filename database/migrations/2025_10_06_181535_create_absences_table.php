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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->string('motif_permi')->nullable();
            $table->string('motif')->nullable();
            $table->string('lieu_travail')->nullable();
            $table->string('heure_debut')->nullable();
            $table->string('heure_fin')->nullable();
            $table->date('date_depart')->nullable();
            $table->date('date_fin')->nullable();
            $table->integer('statut')->default('0')->comment('0=en attente, 1=validée, 2=refusée');;
            $table->integer('nombre_de_jours')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('direction_id')->nullable();
            $table->unsignedBigInteger('filliale_id')->nullable();
            $table->unsignedBigInteger('type_demandes_id')->default('4');
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
        Schema::dropIfExists('absences');
    }
};
