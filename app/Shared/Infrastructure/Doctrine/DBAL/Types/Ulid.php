<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class Ulid extends GuidType
{
    public const NAME = 'ulid';
    public const LENGTH = 26;

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return \Symfony\Component\Uid\Ulid|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return null === $value ? null : new \Symfony\Component\Uid\Ulid($value);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return \is_string($value) ? $value : $value?->getValue();
    }

    /**
     * @param array $column
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = Ulid::LENGTH;
        $column['fixed'] = true;
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
