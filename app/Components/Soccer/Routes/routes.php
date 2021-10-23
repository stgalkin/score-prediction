<?php

use App\Components\Soccer\Ports\Http\Actions\Tournament\CreateTournamentAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\Game\PlayAllGamesAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\Game\PlayNextWeekAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\Game\UpdateGameAction;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'api/tournament'], function() {
    Route::post('/', CreateTournamentAction::class);
    Route::group(['prefix' => '{id}'], function() {
        Route::group(['prefix' => 'weeks'], function() {
            Route::post('next', PlayNextWeekAction::class);
            Route::post('play_all', PlayAllGamesAction::class);
        });
        Route::post('game/{id}', UpdateGameAction::class);
    });
});
