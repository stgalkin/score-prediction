<?php

namespace App\Components\Soccer\Domain\Prediction\Services;

use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;

class PredictionService
{
    /**
     * @param Tournament $entity
     * @param Week $week
     *
     * @return array|string[]
     */
    public function __invoke(Tournament $entity, Week $week)
    {
        $matchesLeft = $entity->weeks() - $week->getValue();

        $predictions = [];

        $winner = null;

        $teams = $entity->teams()->map(fn(Team $team) => [
            'points' => $team->points($week)->getValue(),
            'name' => $team->name()->getValue(),
        ])->getValues();

        usort($teams, fn(array $value, array $nextValue) => $value['points'] < $nextValue['points']);

        foreach ($teams as $team) {
            $currentPoints = $team['points'];
            $maxAllowedPoints = $matchesLeft * Team::WIN_COEFFICIENT;

            // find someone who have more points than current team or can obtain
            $opponents = array_filter($teams,
                fn(array $value) => $value['name'] !== $team['name'] &&
                    (
                        $value['points'] > $team['points'] ||
                        $value['points'] + $maxAllowedPoints > $team['points']
                    )
            );

            $defeatedTeams = array_filter($teams,
                fn(array $value) => $value['name'] !== $team['name'] &&
                    (
                        $value['points'] > $team['points'] + $maxAllowedPoints
                    )
            );

            $delta = $currentPoints + (($currentPoints + $maxAllowedPoints) / 2);

            $predictions[$team['name']] = !is_null($winner) && $winner !== $team['name'] || count($defeatedTeams) === count(
                $teams
            ) - 1 ? 0 : $delta;

            if (count($opponents) === 0) {
                $winner = $team['name'];
            }
        }

        $total = array_sum($predictions);

        return array_map(fn(string $value) => number_format(($value / $total) * 100, 2), $predictions);
    }
}
