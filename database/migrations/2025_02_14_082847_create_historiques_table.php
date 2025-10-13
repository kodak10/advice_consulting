<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur qui a fait l'action
            $table->string('action'); // Description de l'action
            $table->string('entity')->nullable(); // Entité affectée (table ou modèle Laravel)
            $table->unsignedBigInteger('entity_id')->nullable(); // ID de l'entité affectée
            $table->string('ip_address')->nullable(); // IP de l'utilisateur
            $table->text('user_agent')->nullable(); // Navigateur / device
            $table->timestamps();

            // Clé étrangère pour garantir l'intégrité des données
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
