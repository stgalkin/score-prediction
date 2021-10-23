<?php

declare(strict_types=1);

namespace App\Components\Soccer\Ports\Http\Actions\Tournament\Game;

use App\Components\Soccer\Domain\Prediction\Services\PredictionService;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayWeekGames;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Ports\Http\Actions\Traits\ViewResponseTrait;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayNextWeekAction
{
    use ViewResponseTrait;

    /**
     * GetTournamentAction constructor.
     *
     * @param TournamentRepositoryInterface $repository
     * @param PredictionService $service
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
        private PredictionService $service,
        private EntityManagerInterface $manager,
    ) {
    }

    /**
     * @param string $tournamentId
     *
     * @return JsonResponse
     * @throws TournamentNotFoundException
     * @throws GameNotFoundException
     */
    public function __invoke(string $id): string
    {
        $entity = $this->repository->byIdentity(new TournamentId($id));

        $week = $entity->nextNotPlayedWeek();

        \Bus::dispatch(PlayWeekGames::fromArray([
            PlayWeekGames::PROP_TOURNAMENT_ID => $id,
            PlayWeekGames::PROP_WEEK => $entity->nextNotPlayedWeek()->getValue()
        ]));

        $this->manager->clear();

        $entity = $this->repository->byIdentity($entity->id());

        return $this->renderView($entity, $week);
    }
}
