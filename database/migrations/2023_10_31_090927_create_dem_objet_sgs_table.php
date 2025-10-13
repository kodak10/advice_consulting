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
        Schema::create('dem_objet_sgs', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->foreignId('dem_objet_g_id')->contrained()->onDelect('cascade')->nullable();
            $table->integer('numb_de_compte')->nullable();
            $table->foreignId('user_id')->contrained()->onDelect('cascade')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dem_objet_sgs');
    }
};
