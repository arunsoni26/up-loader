<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', action: [DashboardController::class, 'index'])->name('dashboard');
        
        // roles & permissions
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions')->middleware('permission:permissions,can_view');
        Route::post('role-permission-form', [RolePermissionController::class, 'rolePermissionForm'])->name('role-permission-form')->middleware('permission:permissions,can_add');
        Route::post('update-role-permission', [RolePermissionController::class, 'update'])->name('update-role-permission')->middleware('permission:permissions,can_add');
        Route::get('role/{id}/permissions', [RolePermissionController::class, 'getPermissions'])->name('role.get-permissions')->middleware('permission:permissions,can_view');

        Route::group(['prefix' => 'customers', 'as' => 'admin.customers.'], function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::any('/list', [CustomerController::class, 'list'])->name('list');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::post('/store', [CustomerController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [CustomerController::class, 'update'])->name('update');
            Route::post('/toggle-status/{id}', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/toggle-dashboard/{id}', [CustomerController::class, 'toggleDashboard'])->name('toggle-dashboard');
            // Customer form load (Add / Edit)
            Route::post('form', [CustomerController::class, 'form'])->name('form');

            // Customer save (Add / Edit)
            Route::post('save', [CustomerController::class, 'save'])->name('save');

            Route::any('/view', [CustomerController::class, 'view'])->name('view');
        });
        Route::group(['prefix' => 'customers/groups', 'as' => 'admin.customers.groups.'], function () {
            Route::get('groups/list', [CustomerController::class, 'groupList'])->name('list');
            // Customer group form load (Add / Edit)
            Route::post('form', [CustomerController::class, 'groupForm'])->name('form');
            
            // Customer group save (Add / Edit)
            Route::post('save', [CustomerController::class, 'groupSave'])->name('save');
            Route::post('groups/delete', [CustomerController::class, 'groupDelete'])->name('delete');
        });
    });
});