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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('checklist_data')->nullable();
            $table->json('custom_field_data')->nullable();
            $table->decimal('kilometer_input', 10, 2)->nullable();
            $table->decimal('hour_meter_input', 10, 2)->nullable();
            $table->json('foto_paths')->nullable();
            $table->text('catatan_kerusakan')->nullable();
            $table->enum('status', ['submitted', 'approved', 'completed', 'overdue'])->default('submitted');
            $table->enum('tag_type', ['none', 'red_tag', 'white_tag'])->default('none');
            $table->text('tag_description')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['unit_id', 'status', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
