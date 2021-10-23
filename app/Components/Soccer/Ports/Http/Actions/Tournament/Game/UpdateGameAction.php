<?php

declare(strict_types=1);

namespace App\Components\Soccer\Ports\Http\Actions\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\Commands\Game\UpdateGame;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateGameAction
{
    /**
     * PlayAllGamesAction constructor.
     *
     */
    public function __construct(
    ) {
    }

    /**
     * @param Request $request
     * @param string $tournamentId
     * @param string $id
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $tournamentId, string $id): JsonResponse
    {
        \Bus::dispatch(
            UpdateGame::fromArray(
                [
                    UpdateGame::PROP_TOURNAMENT_ID => $tournamentId,
                    UpdateGame::PROP_ID => $id,
                    UpdateGame::PROP_AWAY_GOALS => $request->get('away_goals'),
                    UpdateGame::PROP_HOME_GOALS => $request->get('home_goals'),
                ]
            )
        );

        return new JsonResponse();
    }
}
