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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cours_id');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('fichier'); // Chemin du fichier
            $table->string('type_fichier')->nullable(); // PDF, Word, image, etc.
            $table->unsignedBigInteger('user_id'); // Enseignant qui a publié
            $table->timestamps();

            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
};