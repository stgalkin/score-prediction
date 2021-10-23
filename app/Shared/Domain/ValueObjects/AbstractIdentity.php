<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Symfony\Component\Uid\Ulid;

abstract class AbstractIdentity implements \JsonSerializable
{
    /**
     * @var string
     */
    private string $value;

    /**
     * AbstractIdentity constructor.
     * @param string|null $value
     */
    public function __construct(?string $value = null)
    {
        if (null !== $value && !Ulid::isValid($value)) {
            throw new InvalidArgumentException("Input value '$value' is not valid");
        }
        $this->value = $value ?? (new Ulid())->toBase32();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getValue();
    }

    /**
     * @param AbstractIdentity $other
     * @return bool
     */
    public function equals(AbstractIdentity $other): bool
    {
        return $other->getValue() === $this->getValue();
    }
}
