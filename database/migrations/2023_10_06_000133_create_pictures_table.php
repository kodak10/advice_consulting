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
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_profil_path');
            $table->text('caption')->nullable();
            $table->timestamps();

            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');

            // Vous pouvez également ajouter d'autres colonnes au besoin
            // $table->string('nom_colonne');
            // ...

            // Index, contraintes uniques, etc.
            // $table->unique('colonne_unique');
            // $table->index('colonne_index');
            // ...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};
