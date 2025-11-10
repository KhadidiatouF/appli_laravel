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
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numCompte')->unique()->index();
            $table->decimal('solde', 10, 2)->default(0);
            $table->string('devise')->default('CFA');
            $table->uuid('titulaire');
            $table->date('date_creation')->useCurrent();
            $table->date('date_debut_bloquage')->nullable();
            $table->date('date_fin_bloquage')->nullable();
            $table->enum('statut', ['actif', 'bloqué', 'fermé', 'inactif'])->default('actif');
            $table->timestamps();

            $table->foreign('titulaire')->references('id')->on('clients')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
