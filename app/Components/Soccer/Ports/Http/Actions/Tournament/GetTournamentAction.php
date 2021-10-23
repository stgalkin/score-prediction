<?php

declare(strict_types=1);

namespace App\Components\Soccer\Ports\Http\Actions\Tournament;

use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use App\Components\Soccer\Domain\Tournament\Resources\TournamentResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetTournamentAction
{
    /**
     * GetTournamentAction constructor.
     *
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $entity = $this->repository->byIdentity(new TournamentId('01FJPKM4VKPZV7B1TRRB77Y2KJ'));

        /** @var Team $team */
        $team = $entity->teams()->first();

        $week = new Week(1);
        dd(
            $team->points($week)->getValue(),
            $team->winnings($week)->count(),
            $team->draft($week)->count(),
            $team->lost($week)->count(),
        )
        ;


        return new JsonResponse(TournamentResource::fromEntity($entity));
    }
}
