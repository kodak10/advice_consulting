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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->string('commande')->nullable();
            $table->string('livraison')->nullable();
            $table->string('validite')->nullable();
            $table->string('delai')->nullable();
            $table->foreignId('banque_id')->nullable()->constrained('banques')->onDelete('set null');
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('tva', 10, 2)->default(0.18);
            $table->decimal('total_ttc', 10, 2)->default(0);
            $table->decimal('acompte', 10, 2)->default(0);
            $table->decimal('solde', 10, 2)->default(0);
            $table->string('pdf_path')->nullable();
            $table->string('num_proforma');
            $table->string('status'); 
            $table->foreignId('pays_id')->nullable()->constrained('pays')->onDelete('set null');
            $table->string('devise')->default('XOF');
            $table->text('message')->nullable();  
            $table->text('texte')->nullable();   
            $table->decimal('taux', 10, 4)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
