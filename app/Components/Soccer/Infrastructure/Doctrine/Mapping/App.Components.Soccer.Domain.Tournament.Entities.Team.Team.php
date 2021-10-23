<?php

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\Team\TeamIdType;
use App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Team\NameType;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @var ClassMetadata $metadata
 * @psalm-suppress UndefinedGlobalVariable
 */
$builder = new ClassMetadataBuilder($metadata);

$builder
    ->setTable('tournament_teams')
    // id
    ->createField('id', TeamIdType::NAME)
    ->makePrimaryKey()
    ->build()
    // name
    ->createField('name', NameType::NAME)
    ->build()


    // tournament
    ->createManyToOne('tournament', Tournament::class)
    ->inversedBy('teams')
    ->addJoinColumn('tournament_id', 'id')
    ->build()

    // awayGames
    ->createManyToMany('awayGames', Game::class)
    ->setIndexBy('id')
    ->setJoinTable('tournament_games')
    ->addJoinColumn('away_team_id', 'id')
    ->addInverseJoinColumn('id', 'id')
    ->build()

    // awayGames
    ->createManyToMany('homeGames', Game::class)
    ->setIndexBy('id')
    ->setJoinTable('tournament_games')
    ->addJoinColumn('home_team_id', 'id')
    ->addInverseJoinColumn('id', 'id')
    ->build()
;
