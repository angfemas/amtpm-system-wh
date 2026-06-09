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
        // Add the column only if it doesn't already exist (e.g. added manually).
        if (! Schema::hasColumn('units', 'nomor_urut')) {
            Schema::table('units', function (Blueprint $table) {
                $table->unsignedInteger('nomor_urut')->nullable()->after('id');
                $table->index(['nomor_urut']);
            });
        }

        // Backfill only units that don't have a number yet, continuing from the
        // current max so existing numbers are preserved.
        $counter = (int) DB::table('units')->max('nomor_urut');
        foreach (DB::table('units')->whereNull('nomor_urut')->orderBy('id')->pluck('id') as $id) {
            $counter++;
            DB::table('units')->where('id', $id)->update(['nomor_urut' => $counter]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('units', 'nomor_urut')) {
            Schema::table('units', function (Blueprint $table) {
                $table->dropIndex(['nomor_urut']);
                $table->dropColumn('nomor_urut');
            });
        }
    }
};
