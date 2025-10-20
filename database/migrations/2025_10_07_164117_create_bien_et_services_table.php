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
        Schema::create('bien_et_services', function (Blueprint $table) {
            $table->id();
            $table->integer('montant_demande')->nullable();
            $table->string('motif_permi')->nullable();
            $table->string('motif')->nullable();
            $table->string('detail')->nullable();
            $table->string('payement')->nullable();
            $table->string('lieu_travail')->nullable();
            $table->json('procces_valide_result')->nullable();
            $table->integer('statut')->default('0')->comment('0=en attente, 1=validée, 2=refusée');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('direction_id')->nullable();
            $table->unsignedBigInteger('filliale_id')->nullable();
            $table->unsignedBigInteger('type_demandes_id')->default('1');
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
        Schema::dropIfExists('bien_et_services');
    }
};
