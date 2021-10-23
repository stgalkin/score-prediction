<?php

namespace App\Components\Soccer\Application\Providers;

use App\Components\Soccer\Application\CommandHandlers\Tournament\CreateTournamentHandler;
use App\Components\Soccer\Application\CommandHandlers\Tournament\Game\CreateGameHandler;
use App\Components\Soccer\Application\CommandHandlers\Tournament\Game\PlayAllGamesHandler;
use App\Components\Soccer\Application\CommandHandlers\Tournament\Game\PlayGameHandler;
use App\Components\Soccer\Application\CommandHandlers\Tournament\Game\PlayWeekGamesHandler;
use App\Components\Soccer\Application\CommandHandlers\Tournament\Game\UpdateGameHandler;
use App\Providers\BaseServiceProvider;
use App\Components\Soccer\Domain\Tournament\Commands\CreateTournament;
use App\Components\Soccer\Domain\Tournament\Commands\Game\CreateGame;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayAllGames;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayGame;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayWeekGames;
use App\Components\Soccer\Domain\Tournament\Commands\Game\UpdateGame;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use App\Components\Soccer\Infrastructure\Repositories\Tournament\TournamentDoctrineRepository;
use Bus;

/**
 * Class SoccerServiceProvider
 */
class SoccerServiceProvider extends BaseServiceProvider
{
    private const MODULE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected string $modulePath = self::MODULE_PATH;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootMigrations();
        $this->bootRoutes();
        $this->bootCommandsAndHandlers();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerRepositories();
    }

    /**
     * Register repositories
     *
     * @return void
     */
    protected function registerRepositories(): void
    {
        $this->app->singleton(
            TournamentRepositoryInterface::class,
            TournamentDoctrineRepository::class
        );
    }

    /**
     *
     */
    private function bootCommandsAndHandlers()
    {
        Bus::map(
            [
                CreateTournament::class => CreateTournamentHandler::class,
                CreateGame::class => CreateGameHandler::class,
                UpdateGame::class => UpdateGameHandler::class,
                PlayGame::class => PlayGameHandler::class,
                PlayAllGames::class => PlayAllGamesHandler::class,
                PlayWeekGames::class => PlayWeekGamesHandler::class,
            ]
        );
    }
}
