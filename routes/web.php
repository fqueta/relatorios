<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GerenciarGrupo;
use App\Http\Controllers\GerenciarUsuarios;
use App\Http\Controllers\GerenciarRelatorios;
use App\Http\Controllers\AssistenciaController;
use App\Http\Controllers\PublicadoresController;
use App\Http\Controllers\RoboController;

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

Route::prefix('usuarios')->group(function(){
    Route::get('/',[GerenciarUsuarios::class,'index'])->name('usuarios.index');
    Route::get('/create',[GerenciarUsuarios::class,'create'])->name('usuarios.create');
    Route::post('/',[PublicadoresController::class,'store'])->name('usuarios.store');
    Route::get('/{id}/edit',[GerenciarUsuarios::class,'edit'])->where('id', '[0-9]+')->name('usuarios.edit');
    Route::put('/{id}',[GerenciarUsuarios::class,'update'])->where('id', '[0-9]+')->name('usuarios.update');
    Route::delete('/{id}',[GerenciarUsuarios::class,'destroy'])->where('id', '[0-9]+')->name('usuarios-destroy');

    Route::get('/{id}/cartao',[GerenciarUsuarios::class,'cartao'])->name('usuarios.cartao');
    Route::get('/cards',[GerenciarUsuarios::class,'cards'])->name('usuarios.cards');
    //Route::get('/lista',[GerenciarUsuarios::class,'lista'])->name('publicadores.lista');
});
Route::prefix('publicadores')->group(function(){
    Route::get('/',[PublicadoresController::class,'index'])->name('publicadores.index');
    Route::get('/create',[PublicadoresController::class,'create'])->name('publicadores.create');
    Route::post('/',[PublicadoresController::class,'store'])->name('publicadores.store');
    Route::get('/{id}/edit',[PublicadoresController::class,'edit'])->where('id', '[0-9]+')->name('publicadores.edit');
    Route::put('/{id}',[PublicadoresController::class,'update'])->where('id', '[0-9]+')->name('publicadores.update');
    Route::delete('/{id}',[PublicadoresController::class,'destroy'])->where('id', '[0-9]+')->name('publicadores-destroy');

    Route::get('/{id}/cartao',[PublicadoresController::class,'cartao'])->name('publicadores.cartao');
    Route::get('/cards',[PublicadoresController::class,'cards'])->name('publicadores.cards');
});

Route::prefix('relatorios')->group(function(){
    //Route::get('/',[GerenciarUsuarios::class,'index'])->name('usuarios.index');
    Route::get('/create/{id}',[GerenciarRelatorios::class,'create'])->where('id', '[0-9]+')->name('relatorios.create');
    Route::post('/',[GerenciarRelatorios::class,'store'])->name('relatorios.store');
    //Route::get('/{id}/edit',[GerenciarUsuarios::class,'edit'])->name('usuarios-edit');
    //Route::put('/{id}',[GerenciarRelatorios::class,'update'])->where('id', '[0-9]+')->name('relatorios.update');
    Route::post('/update',[GerenciarRelatorios::class,'update'])->name('relatorios.update');
    //Route::delete('/{id}',[GerenciarUsuarios::class,'destroy'])->where('id', '[0-9]+')->name('usuarios-destroy');
    Route::post('/delete',[GerenciarRelatorios::class,'destroy'])->name('relatorios.destroy');
    Route::post('/registrar/{id}',[GerenciarRelatorios::class,'registrar'])->where('id', '[0-9]+')->name('relatorios.registrar');
});
Route::prefix('robo')->group(function(){
    Route::get('/ler-relatorios',[RoboController::class,'lerRelatorios'])->name('robo.ler-relatorios');
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
Route::prefix('assistencias')->group(function(){
    Route::get('/',[AssistenciaController::class,'index'])->name('assistencias.index');

    Route::get('/ajax',[AssistenciaController::class,'paginacaoAjax'])->name('assistencias.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[AssistenciaController::class,'create'])->name('assistencias.create');
    Route::post('/',[AssistenciaController::class,'store'])->name('assistencias.store');
    Route::get('/{id}/show',[AssistenciaController::class,'show'])->name('assistencias.show');
    Route::get('/{id}/edit',[AssistenciaController::class,'edit'])->name('assistencias.edit');
    Route::put('/{id}',[AssistenciaController::class,'update'])->where('id', '[0-9]+')->name('assistencias.update');
    Route::post('/{id}',[AssistenciaController::class,'update'])->where('id', '[0-9]+')->name('assistencias.update-ajax');
    Route::delete('/{id}',[AssistenciaController::class,'destroy'])->where('id', '[0-9]+')->name('assistencias.destroy');
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
