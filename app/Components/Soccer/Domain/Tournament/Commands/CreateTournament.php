<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands;

class CreateTournament
{
    public const NAME = 'soccer.tournament.create';

    public const PROP_ID = 'id';

    public const REQUIRED_PROPERTIES = [
        self::PROP_ID,
    ];

    /**
     * @param string $id
     */
    public function __construct(
        private string $id,
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @param array $data
     *
     * @return CreateTournament
     */
    public static function fromArray(array $data): CreateTournament
    {
        return new self(
            $data[self::PROP_ID],
        );
    }
}
