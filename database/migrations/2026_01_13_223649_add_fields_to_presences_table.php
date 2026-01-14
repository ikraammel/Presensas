<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->string('statut')->default('present')->after('seance_id'); // present, absent, retard
            $table->string('justificatif')->nullable()->after('statut'); // Chemin du fichier justificatif
            $table->timestamp('date_enregistrement')->useCurrent()->after('justificatif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn(['statut', 'justificatif', 'date_enregistrement']);
        });
    }
};