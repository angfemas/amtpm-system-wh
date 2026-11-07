<?php

use App\Http\Controllers\Admin\HomepageAdminController;
use App\Http\Controllers\Admin\HomepageCarouselController;
use App\Http\Controllers\Admin\HomepageSliderRotateController;
use App\Http\Controllers\Admin\HomepageFlowProcessController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\SystemAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageController;
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

Route::get('/', [HomepageController::class, 'index'])->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/dashboard/data', [DashboardController::class, 'data'])->middleware(['auth'])->name('dashboard.data');
Route::get('/dashboard/export', [DashboardController::class, 'export'])->middleware(['auth'])->name('dashboard.export');
Route::get('/dashboard/export-daily', [DashboardController::class, 'exportDaily'])->middleware(['auth'])->name('dashboard.export-daily');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    Route::post('/profile/toggle-notifications', [ProfileController::class, 'toggleNotifications'])->name('profile.toggle-notifications');
    
    // Master Data Routes
    Route::resource('unit-categories', UnitCategoryController::class)->middleware('permission:categories.view|categories.create|categories.edit|categories.delete');
    Route::post('/unit-categories/{unitCategory}/toggle-status', [UnitCategoryController::class, 'toggleStatus'])->name('unit-categories.toggle-status');
    Route::resource('warehouse-areas', WarehouseAreaController::class)->middleware('permission:areas.view|areas.create|areas.edit|areas.delete');
    Route::post('/warehouse-areas/{warehouseArea}/toggle-status', [WarehouseAreaController::class, 'toggleStatus'])->name('warehouse-areas.toggle-status');
    Route::resource('units', UnitController::class)->middleware('permission:units.view|units.create|units.edit|units.delete');
    Route::post('/units/import', [UnitController::class, 'import'])->name('units.import')->middleware('permission:units.create');
    Route::get('/units/import/template', [UnitController::class, 'downloadImportTemplate'])->name('units.import.template')->middleware('permission:units.create');
    
    // Additional Unit Routes
    Route::post('/units/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    
    // QR Code Routes
    Route::get('qr-codes', [QRCodeController::class, 'index'])->name('qr-codes.index');
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

    // Homepage Management Routes
    Route::prefix('admin/homepage')->name('admin.homepage.')->middleware('permission:homepage.view|homepage.carousel.manage|homepage.slider.manage|homepage.flow.manage|homepage.sections.manage')->group(function () {
        // Main Dashboard
        Route::get('/', [HomepageAdminController::class, 'index'])->name('index')->middleware('permission:homepage.view');

        // Carousel Items
        Route::prefix('carousel')->name('carousel.')->middleware('permission:homepage.carousel.manage')->group(function () {
            Route::get('/', [HomepageCarouselController::class, 'index'])->name('index');
            Route::get('/create', [HomepageCarouselController::class, 'create'])->name('create');
            Route::post('/', [HomepageCarouselController::class, 'store'])->name('store');
            Route::get('/{carousel}/edit', [HomepageCarouselController::class, 'edit'])->name('edit');
            Route::put('/{carousel}', [HomepageCarouselController::class, 'update'])->name('update');
            Route::delete('/{carousel}', [HomepageCarouselController::class, 'destroy'])->name('destroy');
        });

        // Slider Rotate Items (4 Pillars)
        Route::prefix('slider-rotate')->name('slider-rotate.')->middleware('permission:homepage.slider.manage')->group(function () {
            Route::get('/', [HomepageSliderRotateController::class, 'index'])->name('index');
            Route::get('/create', [HomepageSliderRotateController::class, 'create'])->name('create');
            Route::post('/', [HomepageSliderRotateController::class, 'store'])->name('store');
            Route::get('/{slider}/edit', [HomepageSliderRotateController::class, 'edit'])->name('edit');
            Route::put('/{slider}', [HomepageSliderRotateController::class, 'update'])->name('update');
            Route::delete('/{slider}', [HomepageSliderRotateController::class, 'destroy'])->name('destroy');
        });

        // Flow Process Items
        Route::prefix('flow-process')->name('flow-process.')->middleware('permission:homepage.flow.manage')->group(function () {
            Route::get('/', [HomepageFlowProcessController::class, 'index'])->name('index');
            Route::get('/create', [HomepageFlowProcessController::class, 'create'])->name('create');
            Route::post('/', [HomepageFlowProcessController::class, 'store'])->name('store');
            Route::get('/{flow}/edit', [HomepageFlowProcessController::class, 'edit'])->name('edit');
            Route::put('/{flow}', [HomepageFlowProcessController::class, 'update'])->name('update');
            Route::delete('/{flow}', [HomepageFlowProcessController::class, 'destroy'])->name('destroy');
        });

        // Sections
        Route::prefix('sections')->name('sections.')->middleware('permission:homepage.sections.manage')->group(function () {
            Route::get('/', [HomepageSectionController::class, 'index'])->name('index');
            Route::get('/create', [HomepageSectionController::class, 'create'])->name('create');
            Route::post('/', [HomepageSectionController::class, 'store'])->name('store');
            Route::get('/{section}/edit', [HomepageSectionController::class, 'edit'])->name('edit');
            Route::put('/{section}', [HomepageSectionController::class, 'update'])->name('update');
            Route::delete('/{section}', [HomepageSectionController::class, 'destroy'])->name('destroy');
        });
    });

    // System Administration Routes
    Route::prefix('admin/system')->name('admin.system.')->middleware('permission:system.view|system.users.manage|system.roles.manage|system.permissions.manage|system.sections.manage|system.activity.view|system.settings.manage|system.logo.manage')->group(function () {
        // Dashboard
        Route::get('dashboard', [SystemAdminController::class, 'dashboard'])->name('dashboard')->middleware('permission:system.view');

        // Users Management
        Route::get('users', [SystemAdminController::class, 'usersIndex'])->name('users.index')->middleware('permission:system.users.manage');
        Route::get('users/create', [SystemAdminController::class, 'usersCreate'])->name('users.create')->middleware('permission:system.users.manage');
        Route::post('users', [SystemAdminController::class, 'usersStore'])->name('users.store')->middleware('permission:system.users.manage');
        Route::get('users/{user}/edit', [SystemAdminController::class, 'usersEdit'])->name('users.edit')->middleware('permission:system.users.manage');
        Route::put('users/{user}', [SystemAdminController::class, 'usersUpdate'])->name('users.update')->middleware('permission:system.users.manage');
        Route::delete('users/{user}', [SystemAdminController::class, 'usersDestroy'])->name('users.destroy')->middleware('permission:system.users.manage');
        Route::post('users/{user}/toggle-status', [SystemAdminController::class, 'usersToggleStatus'])->name('users.toggle-status')->middleware('permission:system.users.manage');

        // Roles Management
        Route::get('roles', [SystemAdminController::class, 'rolesIndex'])->name('roles.index')->middleware('permission:system.roles.manage');
        Route::get('roles/create', [SystemAdminController::class, 'rolesCreate'])->name('roles.create')->middleware('permission:system.roles.manage');
        Route::post('roles', [SystemAdminController::class, 'rolesStore'])->name('roles.store')->middleware('permission:system.roles.manage');
        Route::get('roles/{role}/edit', [SystemAdminController::class, 'rolesEdit'])->name('roles.edit')->middleware('permission:system.roles.manage');
        Route::put('roles/{role}', [SystemAdminController::class, 'rolesUpdate'])->name('roles.update')->middleware('permission:system.roles.manage');
        Route::delete('roles/{role}', [SystemAdminController::class, 'rolesDestroy'])->name('roles.destroy')->middleware('permission:system.roles.manage');

        // Permissions Management
        Route::get('permissions', [SystemAdminController::class, 'permissionsIndex'])->name('permissions.index')->middleware('permission:system.permissions.manage');
        Route::get('permissions/create', [SystemAdminController::class, 'permissionsCreate'])->name('permissions.create')->middleware('permission:system.permissions.manage');
        Route::post('permissions', [SystemAdminController::class, 'permissionsStore'])->name('permissions.store')->middleware('permission:system.permissions.manage');
        Route::get('permissions/{permission}/edit', [SystemAdminController::class, 'permissionsEdit'])->name('permissions.edit')->middleware('permission:system.permissions.manage');
        Route::put('permissions/{permission}', [SystemAdminController::class, 'permissionsUpdate'])->name('permissions.update')->middleware('permission:system.permissions.manage');
        Route::delete('permissions/{permission}', [SystemAdminController::class, 'permissionsDestroy'])->name('permissions.destroy')->middleware('permission:system.permissions.manage');

        // Sections Management
        Route::get('sections', [SystemAdminController::class, 'sectionsIndex'])->name('sections.index')->middleware('permission:system.sections.manage');
        Route::get('sections/create', [SystemAdminController::class, 'sectionsCreate'])->name('sections.create')->middleware('permission:system.sections.manage');
        Route::post('sections', [SystemAdminController::class, 'sectionsStore'])->name('sections.store')->middleware('permission:system.sections.manage');

        // Activity Log
        Route::get('activity-log', [SystemAdminController::class, 'activityLog'])->name('activity-log')->middleware('permission:system.activity.view');

        // Settings Management
        Route::get('settings', [SystemAdminController::class, 'settingsIndex'])->name('settings.index')->middleware('permission:system.settings.manage');
        Route::put('settings', [SystemAdminController::class, 'settingsUpdate'])->name('settings.update')->middleware('permission:system.settings.manage');

        // Logo Management
        Route::get('logo', [SystemAdminController::class, 'logoIndex'])->name('logo.index')->middleware('permission:system.logo.manage');
        Route::get('logo/create', [SystemAdminController::class, 'logoCreate'])->name('logo.create')->middleware('permission:system.logo.manage');
        Route::post('logo', [SystemAdminController::class, 'logoStore'])->name('logo.store')->middleware('permission:system.logo.manage');
        Route::get('logo/{logo}/edit', [SystemAdminController::class, 'logoEdit'])->name('logo.edit')->middleware('permission:system.logo.manage');
        Route::put('logo/{logo}', [SystemAdminController::class, 'logoUpdate'])->name('logo.update')->middleware('permission:system.logo.manage');
        Route::delete('logo/{logo}', [SystemAdminController::class, 'logoDestroy'])->name('logo.destroy')->middleware('permission:system.logo.manage');
    });
});

require __DIR__.'/auth.php';
