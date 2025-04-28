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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')->constrained('devis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('num_bc')->nullable();
            $table->string('num_rap')->nullable();
            $table->string('num_bl')->nullable();
            $table->string('numero')->nullable();
            $table->decimal('remise_speciale', 10, 2);
            $table->foreignId('pays_id')->nullable()->constrained('pays')->onDelete('set null');
            $table->string('pdf_path')->nullable();
            $table->string('status')->default('Non renseigné'); 
            $table->text('message')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
