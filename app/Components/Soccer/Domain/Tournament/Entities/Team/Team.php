<?php

namespace App\Components\Soccer\Domain\Tournament\Entities\Team;

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamDoesNotParticipateAtTheGameException;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Goals;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Points;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Team
{
    const WIN_COEFFICIENT = 3;

    /**
     * @var Collection
     */
    private Collection $matches;

    /**
     * @var Collection
     */
    private Collection $homeGames;

    /**
     * @var Collection
     */
    private Collection $awayGames;

    public function __construct(
        private Tournament $tournament,
        private TeamId $id,
        private Name $name,
    ) {
        $this->matches = new ArrayCollection();
        $this->awayGames = new ArrayCollection();
        $this->homeGames = new ArrayCollection();
    }

    /**
     * @return TeamId
     */
    public function id(): TeamId
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function matches(): Collection
    {
        return $this->matches;
    }

    /**
     * @param Week|null $week
     *
     * @return Collection
     */
    public function homeGames(?Week $week = null): Collection
    {
        if ($week instanceof Week) {
            return $this->homeGames->filter(fn(Game $game) => $game->week()->equals($week));
        }

        return $this->homeGames;
    }

    /**
     * @param Game $game
     *
     * @return $this
     * @throws TeamDoesNotParticipateAtTheGameException
     */
    public function addHomeGame(Game $game): Team
    {
        if (!$game->homeTeam()->id()->equals($this->id())) {
            throw new TeamDoesNotParticipateAtTheGameException();
        }

        $this->homeGames->set($game->id()->getValue(), $game);

        return $this;
    }

    /**
     * @param Week|null $week
     *
     * @return Collection
     */
    public function awayGames(?Week $week = null): Collection
    {
        if ($week instanceof Week) {
            return $this->awayGames->filter(fn(Game $game) => $game->week()->equals($week));
        }

        return $this->awayGames;
    }

    /**
     * @param Game $game
     *
     * @return $this
     * @throws TeamDoesNotParticipateAtTheGameException
     */
    public function addAwayGame(Game $game): Team
    {
        if (!$game->awayTeam()->id()->equals($this->id())) {
            throw new TeamDoesNotParticipateAtTheGameException();
        }

        $this->awayGames->set($game->id()->getValue(), $game);

        return $this;
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Collection
     */
    public function games(Week $untilWeek = null): Collection
    {
        return new ArrayCollection(array_merge($this->awayGames($untilWeek)->toArray(), $this->homeGames($untilWeek)->toArray()));
    }

    /**
     * @param Week $week
     *
     * @return Collection
     */
    public function gamesForWeek(Week $week): Collection
    {
        return new ArrayCollection(array_merge($this->awayGames($week)->toArray(), $this->homeGames($week)->toArray()));
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Goals
     */
    public function goalsFor(Week $untilWeek = null): Goals
    {
        $goals = array_sum(
            $this->games($untilWeek)->map(
                fn(Game $game) => $game->homeTeam()->id()->equals($this->id()) ? $game->homeGoals()->getValue(
                ) : $game->awayGoals()->getValue()
            )->getValues()
        );

        return new Goals($goals);
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Goals
     */
    public function goalsAgainst(Week $untilWeek = null): Goals
    {
        $goals = array_sum(
            $this->games($untilWeek)->map(
                fn(Game $game) => $game->homeTeam()->id()->equals($this->id()) ? $game->awayGoals()->getValue(
                ) : $game->homeGoals()->getValue()
            )->getValues()
        );

        return new Goals($goals);
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Collection
     */
    public function draft(Week $untilWeek = null): Collection
    {
        return $this->games()->filter(fn(Game $game) => is_null($game->winner()) && (!$untilWeek instanceof Week || $game->week(
                )->getValue() <= $untilWeek->getValue()));
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Collection
     */
    public function winnings(Week $untilWeek = null): Collection
    {
        return $this->games()->filter(
            fn(Game $game) => $game->winner() instanceof Team && $game->winner()->id()->equals($this->id()) && (!$untilWeek instanceof Week || $game->week(
                    )->getValue() <= $untilWeek->getValue())
        );
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Collection
     */
    public function lost(Week $untilWeek = null): Collection
    {
        return $this->games()->filter(
            fn(Game $game) => $game->winner() instanceof Team && !$game->winner()->id()->equals($this->id()) && (!$untilWeek instanceof Week || $game->week(
                    )->getValue() <= $untilWeek->getValue())
        );
    }

    /**
     * @param Week|null $untilWeek
     *
     * @return Points
     */
    public function points(Week $untilWeek = null): Points
    {
        return new Points(($this->winnings($untilWeek)->count() * self::WIN_COEFFICIENT) + $this->draft($untilWeek)->count());
    }
}
