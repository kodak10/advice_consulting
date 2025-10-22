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
        Schema::create('monetiques', function (Blueprint $table) {
            $table->id();
            $table->integer('appel')->nullable();
            $table->string('serie')->nullable();
            $table->string('classe')->nullable();
            $table->string('technicien')->nullable();
            $table->date('date')->nullable();
            $table->string('client')->nullable();
            $table->string('agence')->nullable();
            $table->string('interlocuteur')->nullable();
            $table->string('description')->nullable();
            $table->date('date_intervention')->nullable();
            $table->string('temp_deplacement')->nullable();
            $table->string('temp_intervention')->nullable();
            $table->string('temp_arrêt')->nullable();
            $table->string('temp_remise')->nullable();
            $table->string('arrive_client')->nullable();
            $table->string('debut_intervention')->nullable();
            $table->string('rapport')->nullable();
            $table->string('description_intervention')->nullable();
            $table->string('reference')->nullable();
            $table->string('designation')->nullable();
            $table->integer('quantite')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monetiques');
    }
};
