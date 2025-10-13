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
        Schema::create('notification_demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->contrained()->onDelect('cascade');
            $table->foreignId('travel_id')->contrained()->onDelect('cascade');
            $table->foreignId('travel_request_id')->contrained()->onDelect('cascade');
            $table->integer('user_id')->nullable();
            $table->integer('circuit_id')->nullable();
            $table->integer('organe_id')->nullable();
            $table->integer('order')->nullable();
            $table->string('statut');
            $table->timestamps();
            $table->softDeletes(); // ✅ ajouté ici
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_demandes');
    }
};
