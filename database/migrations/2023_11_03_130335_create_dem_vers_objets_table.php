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
        Schema::create('dem_vers_objets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demandes_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('dem_objets_id')->contrained()->onDelect('cascade')->nullable();
            $table->string('classe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dem_vers_objets');
    }
};
