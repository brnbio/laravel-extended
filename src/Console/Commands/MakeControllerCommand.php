<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;
use Illuminate\Support\Str;
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

    /**
     * @param array $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace): array
    {
        $replace = parent::buildModelReplacements($replace);

        $model = $this->option('model');
        $modelClass = $this->parseModel($model);
        if (class_exists($modelClass)) {
            $replace['{{ modelVariablePlural }}'] = Str::of(class_basename($modelClass))->plural()->lower();
            $replace['{{ baseNamespace }}'] = Str::of($model)->replace('/', '\\')->plural();
        }

        return $replace;
    }
}