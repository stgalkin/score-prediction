<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\ValueObjects\Team;

use JsonSerializable;
use Webmozart\Assert\Assert;

class Name implements JsonSerializable
{
    private const MAX_LENGTH = 255;

    /**
     * Name constructor.
     * @param string $value
     */
    public function __construct(
        private string $value
    ) {
        $this->validate($value);
    }

    /**
     * @param string $value
     */
    private function validate(string $value): void
    {
        Assert::notEmpty($value, "Team name can\'t be empty");

        Assert::maxLength(
            $value,
            self::MAX_LENGTH,
            sprintf(
                "Team nam can\'t be longer than %s symbols",
                self::MAX_LENGTH,
            )
        );
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
        return $this->value;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * @param Name $other
     *
     * @return bool
     */
    public function equals(Name $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
