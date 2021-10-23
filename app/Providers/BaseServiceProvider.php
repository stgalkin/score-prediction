<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Shared\Exceptions\Unexpected\PropertyNotInit;

/**
 * Class BaseServiceProvider
 *
 * @package App\Providers
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $modulePath;

    /**
     * Boot configs
     *
     * @param string $name
     *
     * @return void
     */
    protected function bootConfigs(string $name): void
    {
        $this->mergeConfigRecursiveFrom($this->_modulePath() . 'Config' . DIRECTORY_SEPARATOR . 'config.php', $name);
    }

    /**
     * Boot routes
     *
     * @return void
     */
    protected function bootRoutes(): void
    {
        $this->loadRoutesFrom($this->_modulePath() . 'Routes' . DIRECTORY_SEPARATOR . 'routes.php');
    }

    /**
     *
     */
    protected function bootMigrations(): void
    {
        $this->loadMigrationsFrom($this->_modulePath() . 'Migrations');
    }

    /**
     * Boot channels
     *
     * @return void
     */
    protected function bootChannels(): void
    {
        require $this->_modulePath() . 'Routes' . DIRECTORY_SEPARATOR . 'channels.php';
    }

    /**
     * @return string
     * @throws PropertyNotInit
     */
    private function _modulePath(): string
    {
        if (!is_string($this->modulePath)) {
            throw new PropertyNotInit(__METHOD__, __CLASS__);
        }

        return $this->modulePath;
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param string $path
     * @param string $key
     *
     * @return void
     */
    protected function mergeConfigRecursiveFrom($path, $key): void
    {
        $config = config()->get($key, []);

        config()->set($key, array_merge_recursive(require $path, $config));
    }
}
