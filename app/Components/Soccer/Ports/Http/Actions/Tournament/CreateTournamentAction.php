<?php

declare(strict_types=1);

namespace App\Components\Soccer\Ports\Http\Actions\Tournament;

use App\Components\Soccer\Domain\Tournament\Commands\CreateTournament;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayWeekGames;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Ports\Http\Actions\Traits\ViewResponseTrait;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateTournamentAction
{
    use ViewResponseTrait;

    /**
     * CreateTournamentAction constructor.
     *
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
        private EntityManagerInterface $manager
    ) {
    }

    /**
     * @return string
     * @throws GameNotFoundException
     * @throws TournamentNotFoundException
     */
    public function __invoke(): string
    {
        $id = new TournamentId();

        \Bus::dispatch(
            CreateTournament::fromArray(
                [
                    CreateTournament::PROP_ID => $id->getValue(),
                ]
            )
        );

        $this->manager->clear();

        $entity = $this->repository->byIdentity($id);

        $week = $entity->nextNotPlayedWeek();

        \Bus::dispatch(
            PlayWeekGames::fromArray(
                [
                    PlayWeekGames::PROP_TOURNAMENT_ID => $id->getValue(),
                    PlayWeekGames::PROP_WEEK => $week->getValue(),
                ]
            )
        );

        return $this->renderView($this->repository->byIdentity($id), $week);
    }
}
