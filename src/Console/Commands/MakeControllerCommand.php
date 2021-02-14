<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeControllerCommand
 *
 * @package Brnbio\LaravelExtended\Console\Commands
 */
class MakeControllerCommand extends Command
{
    /**
     * @return array[]
     */
    protected function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = [
            'stub',
            's',
            InputOption::VALUE_OPTIONAL,
            'Generate a controller with the given stub file.',
        ];

        return $options;
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        if ( !empty($stub = $this->option('stub'))) {
            return $this->resolveStubPath($stub);
        }

        return parent::getStub();
    }
}