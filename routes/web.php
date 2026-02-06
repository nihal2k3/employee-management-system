<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([Authenticate::class])->group(function () {
    Route::get('employees', [EmployeeController::class, 'create'])->name('addEmployee');
    Route::post('employees', [EmployeeController::class, 'store_employee'])->name('storeEmployee');
    Route::get('/employees/list', [EmployeeController::class, 'list'])->name('employees.list');
    Route::post('/employees/delete', [EmployeeController::class, 'delete'])->name('employees.delete');
    Route::post('/employees/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::post('/employees/update', [EmployeeController::class, 'update_employee'])->name('employees.update');
    Route::post('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
});
Route::get('/register', [UserController::class, 'register_view'])->name('register');
Route::post('/register', [UserController::class, 'saveregistration'])->name('saveregistration');

Route::get('/login', [UserController::class, 'login_view'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('loginRedirect');
