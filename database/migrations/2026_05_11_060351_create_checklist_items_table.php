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
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_category_id')->constrained('unit_categories')->onDelete('cascade');
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['checkbox', 'text', 'number', 'select'])->default('checkbox');
            $table->json('options')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['unit_category_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
