<?php

namespace App\Components\Soccer\Domain\Tournament\Entities\Game;

use App\Components\Soccer\Domain\Tournament\Exceptions\Game\TeamCantPlayThemSelfException;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Goals;

class Game
{
    /**
     * @var Goals
     */
    private Goals $homeGoals;

    /**
     * @var Goals
     */
    private Goals $awayGoals;

    /**
     * @param Tournament $tournament
     * @param GameId $id
     * @param Team $homeTeam
     * @param Team $awayTeam
     * @param Week $week
     * @param bool $played
     *
     * @throws TeamCantPlayThemSelfException
     */
    public function __construct(
        private Tournament $tournament,
        private GameId $id,
        private Team $homeTeam,
        private Team $awayTeam,
        private Week $week,
        private bool $played = false,
    )
    {
        if ($this->homeTeam->id()->equals($this->awayTeam->id())) {
            throw new TeamCantPlayThemSelfException();
        }

        $this->homeGoals = new Goals(0);
        $this->awayGoals = new Goals(0);
    }

    /**
     * @return GameId
     */
    public function id(): GameId
    {
        return $this->id;
    }

    /**
     * @return Team
     */
    public function homeTeam(): Team
    {
        return $this->homeTeam;
    }

    /**
     * @return Team
     */
    public function awayTeam(): Team
    {
        return $this->awayTeam;
    }

    /**
     * @return Goals
     */
    public function homeGoals(): Goals
    {
        return $this->homeGoals;
    }

    /**
     * @param Goals $goals
     *
     * @return $this
     */
    public function changeHomeGoals(Goals $goals): Game
    {
        $this->homeGoals = $goals;

        return $this;
    }

    /**
     * @return Goals
     */
    public function awayGoals(): Goals
    {
        return $this->awayGoals;
    }

    /**
     * @param Goals $goals
     *
     * @return $this
     */
    public function changeAwayGoals(Goals $goals): Game
    {
        $this->awayGoals = $goals;

        return $this;
    }

    /**
     * @return Week
     */
    public function week(): Week
    {
        return $this->week;
    }

    /**
     * @return Team|null
     */
    public function winner(): ?Team
    {
        return match (true) {
            $this->homeGoals() > $this->awayGoals() => $this->homeTeam(),
            $this->homeGoals() < $this->awayGoals() => $this->awayTeam(),
            default => null,
        };
    }

    /**
     * @return bool
     */
    public function isPlayed(): bool
    {
        return $this->played;
    }

    /**
     * @return Game
     */
    public function played(): Game
    {
        $this->played = true;

        return $this;
    }
}

