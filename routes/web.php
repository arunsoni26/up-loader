<?php

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
    });
});