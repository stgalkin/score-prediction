<?php

declare(strict_types=1);

namespace App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class WeekType extends SmallIntType
{
    public const NAME = 'tournament_game_week';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Week|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Week
    {
        return null === $value ? null : new Week((int)$value);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return is_int($value) ? $value : $value?->getValue();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
