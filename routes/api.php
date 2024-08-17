<?php

use App\Http\Controllers\apicontroller;
use App\Http\Controllers\TipoController;
use App\Models\tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('usuario',[apicontroller::class,'index']);
    Route::get('usuario/search/{name}',[apicontroller::class,'show']);
    Route::delete('usuario/{id}',[apicontroller::class,'destroy']);
    Route::put('usuario/{id}',[apicontroller::class,'update']);

    Route::resource('tipo',TipoController::class);
    Route::get('tipo/list',[TipoController::class,'listado']);
});
Route::post('usuario',[apicontroller::class,'store']);
Route::post('logeo',[apicontroller::class,'logear']);
