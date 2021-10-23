<?php

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\Game\GameIdType;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\Team\TeamIdType;
use App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Game\GoalsType;
use App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Game\WeekType;
use App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Team\NameType;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @var ClassMetadata $metadata
 * @psalm-suppress UndefinedGlobalVariable
 */
$builder = new ClassMetadataBuilder($metadata);

$builder
    ->setTable('tournament_games')
    // id
    ->createField('id', GameIdType::NAME)
    ->makePrimaryKey()
    ->build()

    // awayGoals
    ->createField('awayGoals', GoalsType::NAME)
    ->build()

    // awayGoals
    ->createField('homeGoals', GoalsType::NAME)
    ->build()

    // week
    ->createField('week', WeekType::NAME)
    ->build()

    // week
    ->createField('played', Types::BOOLEAN)
    ->build()

    // tournament
    ->createManyToOne('tournament', Tournament::class)
    ->inversedBy('games')
    ->addJoinColumn('tournament_id', 'id')
    ->build()

    // awayTeam
    ->createOneToOne('awayTeam', Team::class)
    ->addJoinColumn('away_team_id', 'id')
    ->build()

    // homeTeam
    ->createOneToOne('homeTeam', Team::class)
    ->addJoinColumn('home_team_id', 'id')
    ->build()
;
