<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament;

use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Ulid;

class TournamentIdType extends Ulid
{
    public const NAME = 'tournament_id';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return TournamentId|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?TournamentId
    {
        return null === $value ? null : new TournamentId($value);
    }
}
