<?php

namespace App\Components\Soccer\Domain\Tournament\Entities;

use App\Components\Soccer\Domain\Tournament\Exceptions\Game\AllGamesAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentTeamsStaffedException;
use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Tournament
{
    const NUMBER_OF_TEAM_GAMES = 2;
    const MAX_TEAMS_QUANTITY = 4;

    /**
     * @var Collection
     */
    private Collection $games;

    /**
     * @var Collection
     */
    private Collection $teams;

    /**
     * @var Team|null
     */
    private ?Team $champion = null;

    /**
     * @param TournamentId $id
     */
    public function __construct(
        private TournamentId $id
    )
    {
        $this->games = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    /**
     * @return TournamentId
     */
    public function id(): TournamentId
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function teams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     *
     * @return $this
     * @throws TournamentTeamsStaffedException
     */
    public function addTeam(Team $team): Tournament
    {
        if ($this->teams()->count() === self::MAX_TEAMS_QUANTITY) {
            throw new TournamentTeamsStaffedException('Limit of teams are reached.');
        }

        try {
            $this->teamByName($team->name());
        } catch (TeamNotFoundException) {
            $this->teams->set($team->id()->getValue(), $team);
        }

        return $this;
    }

    /**
     * @throws TeamNotFoundException
     */
    public function teamByName(Name $name): Team
    {
        $entity = $this->teams()->filter(fn (Team $team) => $team->name()->equals($name))->first();

        if (!$entity instanceof Team) {
            throw new TeamNotFoundException("Team with name {$name->getValue()} does not exists");
        }

        return $entity;
    }

    /**
     * @throws TeamNotFoundException
     */
    public function teamByIdentity(TeamId $identity): Team
    {
        $entity = $this->teams()->get($identity->getValue());

        if (!$entity instanceof Team) {
            throw new TeamNotFoundException("Team with identity {$identity->getValue()} does not exists");
        }

        return $entity;
    }

    /**
     * @return Collection
     */
    public function games(): Collection
    {
        return $this->games;
    }

    /**
     * @param Week $week
     *
     * @return Collection
     */
    public function gamesByWeek(Week $week): Collection
    {
        return $this->games->filter(fn(Game $game) => $game->week()->equals($week));
    }

    /**
     * @throws TeamNotFoundException
     */
    public function gameByIdentity(GameId $identity): Game
    {
        $entity = $this->games()->get($identity->getValue());

        if (!$entity instanceof Game) {
            throw new TeamNotFoundException("Game with identity {$identity->getValue()} does not exists");
        }

        return $entity;
    }

    /**
     * @param Game $game
     *
     * @return Tournament
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     */
    public function addGame(Game $game): Tournament
    {
        if ($this->games()->count() === ($this->teams()->count() * ($this->teams()->count() -1))) {
            throw new AllGamesAlreadyPlayedException();
        }

        $entity = $this->games()
            ->filter(fn(Game $existed) => $existed->homeTeam()->id()->equals($game->homeTeam()->id())
                && $existed->awayTeam()->id()->equals($game->awayTeam()->id()))->first();

        if ($entity instanceof Game) {
            throw new GameAlreadyPlayedException();
        }

        $this->games()->set($game->id()->getValue(), $game);

        return $this;
    }

    /**
     * @return Team|null
     */
    public function champion(): ?Team
    {
        return $this->champion;
    }

    /**
     * @param Team $team
     *
     * @return $this
     */
    public function end(Team $team): Tournament
    {
        $this->champion = $team;

        return $this;
    }

    /**
     * @return Week
     */
    public function playedWeek(): Week
    {
        $mod = $this->games()->count() % $this->teams()->count();
        $week = $this->games()->count() / $this->teams()->count();

        return new Week($mod === 0 ? $week + 1 : ceil($week));
    }

    /**
     * @return Week
     * @throws GameNotFoundException
     */
    public function nextNotPlayedWeek(): Week
    {
        $games = $this->games()->filter(fn(Game $game) => !$game->isPlayed())->getValues();

        usort($games, fn(Game $game, Game $nextGame) => $game->week()->getValue() > $nextGame->week()->getValue());

        $game = $games[0] ?? null;

        if (!$game instanceof Game) {
            throw new GameNotFoundException();
        }

        return $game->week();
    }

    /**
     * @return int
     */
    public function weeks(): int
    {
        return ($this->teams()->count() * ($this->teams()->count() -1) / self::NUMBER_OF_TEAM_GAMES);
    }
}
