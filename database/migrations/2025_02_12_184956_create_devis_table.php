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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('banque_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->string('num_proforma')->nullable();
            $table->string('num_bc')->nullable();
            $table->string('num_rap')->nullable();
            $table->string('num_bl')->nullable();
            $table->string('ref_designation');
            $table->text('description_designation');
            $table->integer('qte_designation');
            $table->decimal('prixUnitaire_designation', 10, 2);
            $table->decimal('total_designation', 10, 2);
            $table->decimal('remise_speciale', 10, 2)->nullable();
            $table->decimal('totall_ht', 10, 2);
            $table->decimal('tva', 10, 2);
            $table->decimal('total_ttc', 10, 2);
            $table->decimal('accompte', 10, 2)->nullable();
            $table->decimal('solde', 10, 2);
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
