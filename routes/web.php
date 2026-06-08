<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitCategoryController;
use App\Http\Controllers\WarehouseAreaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\RedWhiteTagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Master Data Routes
    Route::resource('unit-categories', UnitCategoryController::class)->middleware('permission:categories.view|categories.create|categories.edit|categories.delete');
    Route::post('/unit-categories/{unitCategory}/toggle-status', [UnitCategoryController::class, 'toggleStatus'])->name('unit-categories.toggle-status');
    Route::resource('warehouse-areas', WarehouseAreaController::class)->middleware('permission:areas.view|areas.create|areas.edit|areas.delete');
    Route::post('/warehouse-areas/{warehouseArea}/toggle-status', [WarehouseAreaController::class, 'toggleStatus'])->name('warehouse-areas.toggle-status');
    Route::get('/units/next-nomor', [UnitController::class, 'nextNomor'])->name('units.next-nomor')->middleware('permission:units.create');
    Route::resource('units', UnitController::class)->middleware('permission:units.view|units.create|units.edit|units.delete');
    Route::post('/units/import', [UnitController::class, 'import'])->name('units.import')->middleware('permission:units.create');
    Route::get('/units/import/template', [UnitController::class, 'downloadImportTemplate'])->name('units.import.template')->middleware('permission:units.create');
    
    // Additional Unit Routes
    Route::post('/units/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    
    // QR Code Routes
    Route::get('qr-codes/generate/{unit}', [QRCodeController::class, 'generate'])->name('qr-codes.generate');
    Route::get('qr-codes/download/{unit}', [QRCodeController::class, 'download'])->name('qr-codes.download');
    Route::get('qr-codes/{unit}', [QRCodeController::class, 'show'])->name('qr-codes.show');
    Route::get('scan', [QRCodeController::class, 'scan'])->name('qr-codes.scan');
    
    // Dynamic System Routes
    Route::resource('checklist-items', ChecklistItemController::class)->middleware('permission:checklist.view|checklist.create|checklist.edit|checklist.delete');
    Route::resource('custom-fields', CustomFieldController::class)->middleware('permission:custom_fields.view|custom_fields.create|custom_fields.edit|custom_fields.delete');
    Route::get('checklist-items/by-category/{category}', [ChecklistItemController::class, 'getByCategory'])->name('checklist-items.by-category');
    Route::get('custom-fields/by-category/{category}', [CustomFieldController::class, 'getByCategory'])->name('custom-fields.by-category');
    
    // Maintenance Flow Routes
    Route::resource('maintenance-logs', MaintenanceLogController::class)->middleware('permission:maintenance.view|maintenance.create|maintenance.edit|maintenance.delete');
    Route::get('maintenance-logs/approve/{maintenanceLog}', [MaintenanceLogController::class, 'approve'])->name('maintenance-logs.approve');
    Route::get('maintenance-logs/complete/{maintenanceLog}', [MaintenanceLogController::class, 'complete'])->name('maintenance-logs.complete');
    Route::get('maintenance-logs/by-status/{status}', [MaintenanceLogController::class, 'getByStatus'])->name('maintenance-logs.by-status');
    Route::get('maintenance-logs/by-unit/{unit}', [MaintenanceLogController::class, 'getByUnit'])->name('maintenance-logs.by-unit');
    
    // Red/White Tag Routes
    Route::resource('red-white-tags', RedWhiteTagController::class)->middleware('permission:tags.view|tags.create|tags.edit|tags.delete|tags.resolve');
    Route::get('red-white-tags/by-type/{type}', [RedWhiteTagController::class, 'getByType'])->name('red-white-tags.by-type');
    Route::get('red-white-tags/open', [RedWhiteTagController::class, 'getOpen'])->name('red-white-tags.open');
    Route::get('red-white-tags/resolved', [RedWhiteTagController::class, 'getResolved'])->name('red-white-tags.resolved');
    Route::get('red-white-tags/overdue', [RedWhiteTagController::class, 'getOverdue'])->name('red-white-tags.overdue');
    Route::get('red-white-tags/resolve/{redWhiteTag}', [RedWhiteTagController::class, 'resolve'])->name('red-white-tags.resolve');
});

require __DIR__.'/auth.php';
