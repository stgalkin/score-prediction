<?php

declare(strict_types=1);

namespace App\Components\Soccer\Infrastructure\Doctrine\DBAL\Types\Tournament\Team;

use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Throwable;

class NameType extends JsonType
{
    public const NAME = 'tournament_team_name';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Name|null
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Name
    {
        try {
            return null === $value ? null : new Name(parent::convertToPHPValue($value, $platform));
        } catch (Throwable $exception) {
            throw new ConversionException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
