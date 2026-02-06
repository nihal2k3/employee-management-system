<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('employees', [EmployeeController::class, 'create']);
Route::post('employees', [EmployeeController::class, 'store_employee'])->name('storeEmployee');
Route::get('/employees/list', [EmployeeController::class, 'list'])->name('employees.list');
Route::post('/employees/delete', [EmployeeController::class, 'delete'])->name('employees.delete');
Route::post('/employees/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::post('/employees/update', [EmployeeController::class, 'update_employee'])->name('employees.update');
Route::post('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');


Route::post('/register', [UserController::class, 'saveregistration'])->name('saveregistration');

