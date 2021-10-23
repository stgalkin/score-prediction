<?php

use App\Components\Soccer\Ports\Http\Actions\Tournament\CreateTournamentAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\Game\PlayAllGamesAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\Game\PlayNextWeekAction;
use App\Components\Soccer\Ports\Http\Actions\Tournament\GetTournamentAction;
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


//Route::get('/', function() {
//
//
//
//
//
//    $service = new \App\Components\Soccer\Domain\Prediction\Services\PredictionService();
//    $repo = app()->make(\App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface::class);
//    $entity = $repo->byIdentity(new \App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId('01FJM6FK74VW2MMR235Q5E306H'));
//
//    $week = new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week(1);
//
//    dd(view('game.content', [
//        'teams' => $entity->teams(),
//        'week' => $week,
//        'predictions' => ($service)($entity, $week),
//        'games' => $entity->gamesByWeek($week),
//    ])->render());
//
//    $team = $entity->teamByIdentity(new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId('01FJM6FK7DKT0FKG46RNE9KVFD'));
//
////    dd($team->games()->map(fn (\App\Components\Soccer\Domain\Tournament\Entities\Game\Game $game) => $game->winner()));
//    dd(($service)($entity, new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week(5)));
//
//    Bus::dispatch(new \App\Components\Soccer\Domain\Tournament\Commands\Game\PlayAllGames('01FJM6FK74VW2MMR235Q5E306H'));
////    $repo = app()->make(\App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface::class);
////
////
////    $entity = $repo->byIdentity(new \App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId('01FJKXC20DAFXMCFWZTR7SQPK7'));
////
////    $team = $entity->teamByName(new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name('4'));
//////    dd(\App\Components\Soccer\Domain\Tournament\Resources\TournamentResource::fromEntity($entity));
////
////
////    dd($entity->playedWeek());
////
////    dd(4 % $entity->teams()->count());
////
////    dd($team->awayGames()->first());
//
////    dd()->awayGames());
//
////    $entity = new \App\Components\Soccer\Domain\Tournament\Entities\Tournament(new \App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId());
////
////    $entity
////        ->addTeam(new \App\Components\Soccer\Domain\Tournament\Entities\Team\Team($entity, new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId(), new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name('1')))
////        ->addTeam(new \App\Components\Soccer\Domain\Tournament\Entities\Team\Team($entity, new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId(), new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name('2')))
////        ->addTeam(new \App\Components\Soccer\Domain\Tournament\Entities\Team\Team($entity, new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId(), new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name('3')))
////        ->addTeam(new \App\Components\Soccer\Domain\Tournament\Entities\Team\Team($entity, new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId(), new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name('4')));
////
////
////    $entity->addGame(new \App\Components\Soccer\Domain\Tournament\Entities\Game\Game($entity, new \App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId(), $entity->teams()->first(), $entity->teams()->last(), new \App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week(1)));
////
////    $repo->save($entity);
//
//});
Route::group(['prefix' => 'api/tournament'], function() {
    Route::get('/', GetTournamentAction::class);
    Route::post('/', CreateTournamentAction::class);
    Route::group(['prefix' => '{id}'], function() {
        Route::get('/', );
        Route::group(['prefix' => 'weeks'], function() {
            Route::post('next', PlayNextWeekAction::class);
            Route::post('play_all', PlayAllGamesAction::class);
        });
    });
});
