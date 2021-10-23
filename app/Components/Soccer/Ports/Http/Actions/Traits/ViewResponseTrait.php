<?php

namespace App\Components\Soccer\Ports\Http\Actions\Traits;

use App\Components\Soccer\Domain\Prediction\Services\PredictionService;
use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;

trait ViewResponseTrait
{
    /**
     * @param Tournament $entity
     * @param Week $week
     *
     * @return string
     */
    public function renderView(Tournament $entity, Week $week, bool $renderButtons = true): string
    {
        return view('game.content', [
            'tournamentId' => $entity->id(),
            'teams' => $entity->teams(),
            'week' => $week,
            'predictions' => ($this->_service())($entity, $week),
            'games' => $entity->gamesByWeek($week),
            'renderButtons' => $renderButtons && $entity->weeks() !== $week->getValue()
        ]);
    }

    /**
     * @return PredictionService
     */
    private function _service(): PredictionService
    {
        return new PredictionService();
    }
}
