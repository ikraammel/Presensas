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
        Schema::table('seances', function (Blueprint $table) {
            $table->string('type')->default('présentiel')->after('date_fin'); // présentiel ou en ligne
            $table->string('qr_token')->nullable()->after('type'); // Token unique pour QR code
            $table->timestamp('qr_expires_at')->nullable()->after('qr_token'); // Date d'expiration du QR
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropColumn(['type', 'qr_token', 'qr_expires_at']);
        });
    }
};