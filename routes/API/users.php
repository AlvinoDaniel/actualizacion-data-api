<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::group([
	'middleware' => 'api'
], function () {
	Route::group([
		'prefix'=>'user'],function(){
			Route::middleware(['auth:sanctum'])->group(function () {
                Route::get('backup', [UserController::class, 'backupDownload']);
                Route::post('/actualizar', [UserController::class, 'update']);
                Route::post('/actualizar/{id}/contrasena', [UserController::class, 'update_password']);
			});
		});
});
