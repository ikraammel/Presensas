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
        Schema::table('etudiants', function (Blueprint $table) {
            $table->string('cne')->nullable()->after('noet');
            $table->unsignedBigInteger('groupe_id')->nullable()->after('cne');

            $table->foreign('groupe_id')->references('id')->on('groupes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropForeign(['groupe_id']);
            $table->dropColumn(['cne', 'groupe_id']);
        });
    }
};