<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours_groupes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cours_id');
            $table->unsignedBigInteger('groupe_id');
            $table->timestamps();

            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->foreign('groupe_id')->references('id')->on('groupes')->onDelete('cascade');
            $table->unique(['cours_id', 'groupe_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours_groupes');
    }
};
