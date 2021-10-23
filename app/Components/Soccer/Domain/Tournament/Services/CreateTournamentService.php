<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services;

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\AllGamesAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\TeamCantPlayThemSelfException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentTeamsStaffedException;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Commands\CreateTournament;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name;
use Doctrine\ORM\OptimisticLockException;

final class CreateTournamentService
{
    const AVAILABLE_TEAMS = [
        'Liverpool',
        'Man City',
        'Man Utd',
        'Chelsea',
        'Southampton',
        'Spurs',
        'Everton',
        'Arsenal',
    ];

    const WEEK_MAP = [
        1 => [
            1 => [
                0, 1
            ],
            2 => [
                2, 3
            ]
        ],
        2 => [
            1 => [
                1,0
            ],
            2 => [
                3, 2
            ]
        ],
        3 => [
            1 => [
                0,2
            ],
            2 => [
                1, 3
            ]
        ],
        4 => [
            1 => [
                2,0
            ],
            2 => [
                3, 1
            ]
        ],
        5 => [
            1 => [
                0, 3
            ],
            2 => [
                2, 1
            ]
        ],
        6 => [
            1 => [
                3, 0
            ],
            2 => [
                1, 2
            ]
        ],
    ];
    /**
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param CreateTournament $command
     *
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     * @throws OptimisticLockException
     * @throws TeamCantPlayThemSelfException
     * @throws TournamentTeamsStaffedException
     */
    public function __invoke(CreateTournament $command): void
    {
        $entity = new Tournament(
            id: new TournamentId($command->getId()),
        );

        $teams = self::AVAILABLE_TEAMS;
        shuffle($teams);

        $teams = array_slice($teams, 0, 4);

        foreach ($teams as $name) {
            $team = new Team(
                tournament: $entity,
                id:         new TeamId(),
                name:       new Name($name)
            );

            $entity->addTeam($team);
        }

        $teams = clone $entity->teams();

        $candidates = $entity->teams()->getValues();

        foreach (self::WEEK_MAP as $week => $group) {
            foreach($group as $teamKeys) {
                $this->playWithEachOther($entity, array_intersect_key($candidates, array_flip($teamKeys)), new Week($week));
            }
        }

        $this->repository->save($entity);
    }

    /**
     * @param Tournament $tournament
     * @param array $teams
     * @param Week $week
     *
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     * @throws TeamCantPlayThemSelfException
     */
    private function playWithEachOther(Tournament $tournament, array $teams, Week $week): void
    {
        $teams = array_values($teams);

        if (count($teams) !== 2) {
            throw new \InvalidArgumentException('Teams array doesn\'t contanis 2 teams.');
        }

        $this->addTeam($tournament, $teams[0], $teams[1], $week);
    }

    /**
     * @param Tournament $tournament
     * @param Team $homeTeam
     * @param Team $awayTeam
     * @param Week $week
     *
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     * @throws TeamCantPlayThemSelfException
     */
    private function addTeam(Tournament $tournament, Team $homeTeam, Team $awayTeam, Week $week): void
    {
        $game = new Game($tournament, new GameId(), $homeTeam, $awayTeam, $week);

        try {
            $tournament->addGame($game);
        } catch (GameAlreadyPlayedException $exception) {
            $tournament->addGame(new Game($tournament, new GameId(), $awayTeam, $homeTeam, $week));
        }
    }
}

