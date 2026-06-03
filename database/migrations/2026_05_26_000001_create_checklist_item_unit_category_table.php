<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checklist_item_unit_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->foreignId('unit_category_id')->constrained('unit_categories')->onDelete('cascade');
            $table->unique(['checklist_item_id', 'unit_category_id'], 'ciuc_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('checklist_item_unit_category');
    }
};
