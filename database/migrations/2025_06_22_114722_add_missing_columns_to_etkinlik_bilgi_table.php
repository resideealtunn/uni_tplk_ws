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
        Schema::table('etkinlik_bilgi', function (Blueprint $table) {
            if (!Schema::hasColumn('etkinlik_bilgi', 'bitis_tarihi')) {
                $table->datetime('bitis_tarihi')->nullable()->after('tarih');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etkinlik_bilgi', function (Blueprint $table) {
            if (Schema::hasColumn('etkinlik_bilgi', 'bitis_tarihi')) {
                $table->dropColumn('bitis_tarihi');
            }
        });
    }
};
