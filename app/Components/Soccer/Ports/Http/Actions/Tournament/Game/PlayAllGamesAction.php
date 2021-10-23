<?php

declare(strict_types=1);

namespace App\Components\Soccer\Ports\Http\Actions\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayAllGames;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Components\Soccer\Ports\Http\Actions\Traits\ViewResponseTrait;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class PlayAllGamesAction
{
    use ViewResponseTrait;

    /**
     * PlayAllGamesAction constructor.
     *
     * @param TournamentRepositoryInterface $repository
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
        private EntityManagerInterface $manager,
    ) {
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function __invoke(string $id)
    {
        $entity = $this->repository->byIdentity(new TournamentId($id));

        $currentWeek = $entity->nextNotPlayedWeek();

        \Bus::dispatch(
            PlayAllGames::fromArray(
                [
                    PlayAllGames::PROP_TOURNAMENT_ID => $id,
                ]
            )
        );

        $this->manager->clear();

        $entity = $this->repository->byIdentity(new TournamentId($id));

        $response = '';

        for ($i = $currentWeek->getValue(); $i <= $entity->weeks(); $i++) {
            $response .= $this->renderView($entity, new Week($i), false);
        }

        return $response;
    }
}
