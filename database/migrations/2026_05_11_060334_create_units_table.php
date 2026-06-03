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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit', 50)->unique();
            $table->string('nama_unit', 255);
            $table->foreignId('unit_category_id')->nullable()->constrained('unit_categories')->nullOnDelete();
            $table->foreignId('warehouse_area_id')->nullable()->constrained('warehouse_areas')->nullOnDelete();
            $table->enum('jenis_maintenance', ['preventive', 'corrective', 'predictive'])->default('preventive');
            $table->date('tanggal_maintenance_terakhir')->nullable();
            $table->integer('interval_hari')->default(30);
            $table->decimal('kilometer', 10, 2)->nullable();
            $table->decimal('hour_meter', 10, 2)->nullable();
            $table->enum('status', ['active', 'maintenance', 'overdue', 'inactive'])->default('active');
            $table->string('qr_code', 100)->unique()->nullable();
            $table->string('foto_unit', 500)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['kode_unit']);
            $table->index(['status', 'is_active']);
            $table->index(['unit_category_id']);
            $table->index(['warehouse_area_id']);
            $table->index(['jenis_maintenance']);
            $table->index(['tanggal_maintenance_terakhir']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
