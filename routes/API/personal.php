<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;



Route::group([
    'middleware'  => 'api',
    'prefix'      => 'personal'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(PersonalController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('/', 'store');
      Route::get('/donwload/list', 'genareteList');
      Route::get('/donwload-by-nucleo/list', 'genareteReport')->middleware('admin');
      Route::get('/export/list', 'exportAllPersonal')->middleware('admin');
      Route::post('/{id}', 'update');
      Route::delete('/{id}', 'destroy');
      Route::get('/search/{cedula}', 'search');
      Route::get('/all-by-unidad', 'personalAllUnidad');
    });
  });
});
