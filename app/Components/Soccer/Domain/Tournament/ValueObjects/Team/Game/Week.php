<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game;

use JsonSerializable;
use Webmozart\Assert\Assert;

class Week implements JsonSerializable
{
    /**
     * Week constructor.
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
        Assert::greaterThan(
            $value,
            0,
            sprintf(
                "Week should be greater than %s",
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
     * @param Week $other
     *
     * @return bool
     */
    public function equals(Week $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
