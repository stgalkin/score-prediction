<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\Game;

use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Ulid;

class GameIdType extends Ulid
{
    public const NAME = 'tournament_game_id';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return GameId|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?GameId
    {
        return null === $value ? null : new GameId($value);
    }
}
