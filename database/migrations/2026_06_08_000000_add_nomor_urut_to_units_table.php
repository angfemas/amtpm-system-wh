<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedInteger('nomor_urut')->nullable()->after('id');
            $table->index(['nomor_urut']);
        });

        // Backfill existing units with sequential numbers ordered by id
        // so every unit already has an identity number.
        $counter = 0;
        foreach (DB::table('units')->orderBy('id')->pluck('id') as $id) {
            $counter++;
            DB::table('units')->where('id', $id)->update(['nomor_urut' => $counter]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['nomor_urut']);
            $table->dropColumn('nomor_urut');
        });
    }
};
