<?php

declare(strict_types=1);

namespace App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Goals;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class GoalsType extends SmallIntType
{
    public const NAME = 'tournament_game_goals';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Goals|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Goals
    {
        return null === $value ? null : new Goals((int)$value);
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
