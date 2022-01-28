<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GerenciarGrupo;
use App\Http\Controllers\GerenciarUsuarios;
use App\Http\Controllers\GerenciarRelatorios;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('grupos')->group(function(){
    Route::get('/',[GerenciarGrupo::class,'index'])->name('grupos-index');
    Route::get('/create',[GerenciarGrupo::class,'create'])->name('grupos-create');
    Route::post('/',[GerenciarGrupo::class,'store'])->name('grupos-store');
    Route::get('/{id}/edit',[GerenciarGrupo::class,'edit'])->where('id', '[0-9]+')->name('grupos-edit');
    Route::put('/{id}',[GerenciarGrupo::class,'update'])->where('id', '[0-9]+')->name('grupos-update');
    Route::delete('/{id}',[GerenciarGrupo::class,'destroy'])->where('id', '[0-9]+')->name('grupos-destroy');
});

Route::prefix('publicadores')->group(function(){
    Route::get('/',[GerenciarUsuarios::class,'index'])->name('usuarios.index');
    Route::get('/create',[GerenciarUsuarios::class,'create'])->name('usuarios.create');
    Route::post('/',[GerenciarUsuarios::class,'store'])->name('usuarios.store');
    Route::get('/{id}/edit',[GerenciarUsuarios::class,'edit'])->where('id', '[0-9]+')->name('usuarios.edit');
    Route::put('/{id}',[GerenciarUsuarios::class,'update'])->where('id', '[0-9]+')->name('usuarios.update');
    Route::delete('/{id}',[GerenciarUsuarios::class,'destroy'])->where('id', '[0-9]+')->name('usuarios-destroy');

    Route::get('/{id}/cartao',[GerenciarUsuarios::class,'cartao'])->name('usuarios.cartao');
    Route::get('/cards',[GerenciarUsuarios::class,'cards'])->name('usuarios.cards');
});

Route::prefix('relatorios')->group(function(){
    //Route::get('/',[GerenciarUsuarios::class,'index'])->name('usuarios.index');
    Route::get('/create',[GerenciarRelatorios::class,'create'])->name('relatorios.create');
    Route::post('/',[GerenciarRelatorios::class,'store'])->name('relatorios.store');
    //Route::get('/{id}/edit',[GerenciarUsuarios::class,'edit'])->where('id', '[0-9]+')->name('usuarios-edit');
    //Route::put('/{id}',[GerenciarRelatorios::class,'update'])->where('id', '[0-9]+')->name('relatorios.update');
    Route::post('/update',[GerenciarRelatorios::class,'update'])->name('relatorios.update');
    //Route::delete('/{id}',[GerenciarUsuarios::class,'destroy'])->where('id', '[0-9]+')->name('usuarios-destroy');
    Route::delete('/',[GerenciarUsuarios::class,'destroy'])->name('usuarios.destroy');

    //Route::get('/{id}/cartao',[GerenciarUsuarios::class,'cartao'])->name('usuarios.cartao');
    //Route::get('/cards',[GerenciarUsuarios::class,'cards'])->name('usuarios.cards');
});

Route::prefix('admin')->group(function(){
  Route::get('/profile',[AdminConfigController::class,'index'])->name('admin.profile');
  Route::get('/password',[AdminConfigController::class,'password'])->name('admin.password');
});
Route::prefix('users')->group(function(){
    Route::get('/',[UserController::class,'index'])->name('users.index');

    Route::get('/ajax',[UserController::class,'paginacaoAjax'])->name('users.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[UserController::class,'create'])->name('users.create');
    Route::post('/',[UserController::class,'store'])->name('users.store');
    Route::get('/{id}/show',[UserController::class,'show'])->where('id', '[0-9]+')->name('users.show');
    Route::get('/{id}/edit',[UserController::class,'edit'])->where('id', '[0-9]+')->name('users.edit');
    Route::put('/{id}',[UserController::class,'update'])->where('id', '[0-9]+')->name('users.update');
    Route::delete('/{id}',[UserController::class,'destroy'])->where('id', '[0-9]+')->name('users.destroy');
});

Route::fallback(function () {
    return "Página não encontrada!";
});
Route::get('/teste',[App\Http\Controllers\HomeController::class,'teste'])->name('teste');
Route::post('/upload',[App\Http\Controllers\UploadFile::class,'upload'])->name('teste.upload');

Auth::routes();

Route::get('/',function(){
  return redirect()->route('login');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
