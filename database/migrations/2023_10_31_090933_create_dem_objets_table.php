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
        Schema::create('dem_objets', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('nombre_de_jour')->nullable();
            $table->foreignId('dem_objet_g_id')->contrained()->onDelect('cascade')->nullable();
            $table->foreignId('dem_objet_sg_id')->contrained()->onDelect('cascade')->nullable();
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
        Schema::dropIfExists('dem_objets');
    }
};
