<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended;

use Brnbio\LaravelExtended\Console\Commands\MakeRouteCommand;
use Brnbio\LaravelExtended\Console\Commands\MakeViewCommand;
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
        ]);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        //
    }
}
