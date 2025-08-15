<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/settings/update', [ProfileController::class, 'updateProfile'])->name('settings.update');
Route::post('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');

//news
Route::post('/news/add', [ProfileController::class, 'addNews'])->name('news.add');
Route::put('/news/{id}', [ProfileController::class, 'update'])->name('news.update');
Route::delete('/news/{id}', [ProfileController::class, 'destroy'])->name('news.destroy');
Route::patch('/news/{id}/restore', [ProfileController::class, 'restore'])->name('news.restore');

//gallery
Route::post('/banner/add', [ProfileController::class, 'addBanner'])->name('banner.add');
Route::delete('/banner/delete/{id}', [ProfileController::class, 'deleteBanner'])->name('banner.delete');
Route::patch('/banner/restore/{id}', [ProfileController::class, 'restoreBanner'])->name('banner.restore');

Route::middleware(['auth', 'role.superadmin'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions');
        Route::post('role-permission-form', [RolePermissionController::class, 'rolePermissionForm'])->name('role-permission-form');
        Route::post('update-role-permission', [RolePermissionController::class, 'update'])->name('update-role-permission');
        Route::get('role/{id}/permissions', [RolePermissionController::class, 'getPermissions'])->name('role.get-permissions');
    });
});
