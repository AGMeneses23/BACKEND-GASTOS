<?php

use App\Http\Controllers\CategoriasController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/categorias/lista', [CategoriasController::class, 'index'])->name('categoria.lista');
Route::get('/categoria/nueva', [CategoriasController::class, 'create'])->name('categoria.nueva');
Route::get('/categoria/editar/{id}', [CategoriasController::class, 'edit'])->name('categoria.editar');
Route::get('/categoria/eliminar/{id}', [CategoriasController::class, 'delete'])->name('categoria.eliminar');
Route::post('/categoria/guardar', [CategoriasController::class, 'store'])->name('categoria.guardar');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
