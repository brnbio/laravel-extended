<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended;

use Brnbio\LaravelExtended\Console\Commands\MakeControllerCommand;
use Brnbio\LaravelExtended\Console\Commands\MakeCrudCommand;
use Brnbio\LaravelExtended\Console\Commands\MakeRouteCommand;
use Brnbio\LaravelExtended\Console\Commands\MakeViewCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

/**
 * Class ExtendedServiceProvider
 *
 * @package Brnbio\LaravelExtended
 */
class ExtendedServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            MakeViewCommand::class,
            MakeRouteCommand::class,
            MakeControllerCommand::class,
            MakeCrudCommand::class,
        ]);

        $this->publishes([__DIR__ . '/../stubs' => base_path('stubs/')]);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->extend('command.controller.make', function () {
            return new MakeControllerCommand(new Filesystem());
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'command.controller.make',
        ];
    }
}
