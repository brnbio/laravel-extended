<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeRouteCommand
 *
 * @package Brnbio\LaravelExtended\Console\Commands
 */
class MakeRouteCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'make:route';

    /**
     * @var string
     */
    protected $description = 'Create a new route';

    /**
     * @return int
     */
    public function handle(): int
    {
        $route = $this->_generateRoute();
        $this->_addRouteToRouteFile($route);

        return 0;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'link',
                InputArgument::REQUIRED,
                'The url of this route',
            ],
            [
                'controller',
                InputArgument::REQUIRED,
                'Controller class of the route',
            ],
            [
                'action',
                InputArgument::OPTIONAL,
                'Name of the function if the class is not invokable',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            [
                'post',
                'p',
                InputOption::VALUE_NONE,
                'Generate a post route',
            ],
            [
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Name of the route',
                false,
            ],
            [
                'file',
                null,
                InputOption::VALUE_OPTIONAL,
                'Name of the route file',
                'web',
            ],
        ];
    }

    /**
     * @return string
     */
    private function _generateRoute(): string
    {
        $method = $this->option('post') ? 'post' : 'get ';
        $link = '/' . $this->argument('link');
        $class = $this->_getControllerClass();

        if ($name = $this->option('name')) {
            $name = '->name(\'' . $name . '\')';
        }

        return "Route::$method('$link', $class)$name;";
    }

    /**
     * @param string $route
     * @return void
     */
    private function _addRouteToRouteFile(string $route): void
    {
        $routeFilename = app()->basePath('routes/') . $this->option('file') . '.php';
        $file = File::get($routeFilename);
        if (strpos($file, $route)) {
            $this->error('Route already exists!');
        } else {
            File::append($routeFilename, "\n$route");
        }
    }

    /**
     * @return string
     */
    private function _getControllerClass(): string
    {
        $class = 'Controllers\\' . str_replace('/', '\\', $this->argument('controller')) . '::class';

        if ($action = $this->argument('action')) {
            return "[$class, '$action']";
        }

        return $class;
    }
}