<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CargoPersonalController;
use App\Http\Controllers\NucleoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\UnidadAdminController;
use App\Http\Controllers\UnidadEjecutoraController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
	'middleware' => 'api'
], function () {
    Route::post('/search-worker', [UserController::class, 'searchWorker']);
    Route::post('/search-user', [UserController::class, 'search_email']);
    Route::post('/send-email-reset', [UserController::class, 'sendEmailReset']);
    Route::post('/resend-email-reset', [UserController::class, 'resendEmailReset']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::group([
      'prefix'=>'auth'],function(){
        Route::post('login',[AuthController::class, 'login']);
        Route::post('/register', [UserController::class, 'store']);
        //  Route::post('/reset-password',[AuthController::class, 'sendResetPasswordEmail']);
         Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::get('/all-permissions', [AuthController::class, 'allPermissions'])->name('allPermissions')->middleware('admin');
            Route::post('/register-permissions', [AuthController::class, 'assignPermissions'])->name('assignPermissions')->middleware('admin');
            Route::get('/logout', [AuthController::class, 'logout']);
            // Route::get('/revoketoken', [AuthController::class, 'RevokeToken']);
            Route::post('/changepassword', [AuthController::class, 'changePassword']);
         });
   });
});



Route::group([
   'middleware'  => 'api',
], function () {
   Route::middleware(['auth:sanctum'])->group(function () {
      Route::post('/catalogue', CatalogueController::class);
   });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'nucleo'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(NucleoController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store')->middleware('transform.upper');
        Route::post('/update/{id}', 'update')->middleware('transform.upper');
        Route::delete('/delete/{id}', 'destroy');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'unidad'
], function () {
  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(UnidadEjecutoraController::class)->group(function () {
        Route::get('ejecutora/', 'index');
        Route::post('ejecutora/store', 'store')->middleware('transform.upper');
        Route::post('ejecutora/update/{id}', 'update')->middleware('transform.upper');
        Route::delete('ejecutora/{id}', 'destroy');
    });
    Route::controller(UnidadAdminController::class)->group(function () {
        Route::get('administrativa/', 'index');
        Route::post('administrativa/store', 'store')->middleware('transform.upper');
        Route::post('administrativa/update/{id}', 'update')->middleware('transform.upper');
        Route::delete('administrativa/{id}', 'destroy');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'cargo-personal'
], function () {
  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(CargoPersonalController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('/store', 'store')->middleware('transform.upper');
      Route::post('/update/{id}', 'update')->middleware('transform.upper');
      Route::delete('/delete/{id}', 'destroy');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'personal'
], function () {
  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(PersonalController::class)->group(function () {
      Route::post('/importar-masivo', 'importarMasivo');
      Route::get('/by-cedula/{cedula}', 'getByCedula');
      Route::get('/export-plantilla', 'exportarPlantillaImportacion');
      Route::post('/update-unidades/{id}', 'actualizarUnidadPersonal');
    });
  });
});
