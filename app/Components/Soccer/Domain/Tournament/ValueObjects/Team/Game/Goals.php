<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game;

use JsonSerializable;
use Webmozart\Assert\Assert;

class Goals implements JsonSerializable
{
    private const MAX_VALUE = 99;

    /**
     * Name constructor.
     *
     * @param int $value
     */
    public function __construct(
        private int $value
    ) {
        $this->validate($value);
    }

    /**
     * @param int $value
     */
    private function validate(int $value): void
    {

        Assert::lessThanEq(
            $value,
            self::MAX_VALUE,
            sprintf(
                "Goals can\'t be greater or equals than %s",
                self::MAX_VALUE,
            )
        );

        Assert::greaterThanEq(
            $value,
            0,
            sprintf(
                "Goals should be greater or equals than %s",
                0,
            )
        );
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    /**
     * @return int
     */
    public function jsonSerialize(): int
    {
        return $this->value;
    }

    /**
     * @param Goals $other
     *
     * @return bool
     */
    public function equals(Goals $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
