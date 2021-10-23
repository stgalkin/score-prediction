<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\Team;

use App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Ulid;

class TeamIdType extends Ulid
{
    public const NAME = 'tournament_team_id';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return TeamId|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?TeamId
    {
        return null === $value ? null : new TeamId($value);
    }
}
