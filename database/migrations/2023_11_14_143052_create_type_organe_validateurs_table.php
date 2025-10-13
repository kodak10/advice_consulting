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
        Schema::create('type_organe_validateurs', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->foreignId('organe_validateur_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('circuit_organe_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('filliale_id')->contrained()->onDelect('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_organe_validateurs');
    }
};
