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
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('devis_id')->constrained('devis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('num_bc')->nullable();
            $table->string('num_rap')->nullable();
            $table->string('num_bl')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            //
        });
    }
};
