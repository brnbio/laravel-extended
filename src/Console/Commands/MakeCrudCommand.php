<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * Class MakeCrudCommand
 *
 * @package Brnbio\LaravelExtended\Console\Commands
 */
class MakeCrudCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:crud {model}';

    /**
     * @var string
     */
    protected $description = 'Create all crud files for a given model';

    /**
     * @var Stringable
     */
    protected Stringable $model;

    /**
     * @var Stringable
     */
    protected Stringable $modelClass;

    /**
     * @var Stringable
     */
    protected Stringable $namespace;

    /**
     * @var Stringable
     */
    protected Stringable $path;

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->model = Str::of($this->argument('model'));
        $this->namespace = $this->model->plural();
        $this->path = $this->namespace->lower();
        $this->modelClass = Str::of($this->model->explode('/')->last());

        $this->createModel();
        $this->createActionRead();
        $this->createActionCreate();
        $this->createActionUpdate();
        $this->createActionDelete();

        return 0;
    }

    /**
     * @return void
     */
    protected function createModel(): void
    {
        $this->call('make:migration', ['name' => 'Create' . $this->namespace->replace('/', '') . 'Table']);
        $this->call('make:model', [
            'name'      => $this->model,
            '--factory' => true,
            '--seed'    => true,
        ]);
    }

    /**
     * @return void
     */
    protected function createActionCreate(): void
    {
        $controller = $this->namespace . '/CreateController';

        $this->call('make:controller', [
            'name'    => $controller,
            '--model' => $this->model,
            '--stub'  => 'stubs/controller.model.create.stub',
        ]);
        $this->call('make:request', ['name' => $this->namespace . '/StoreRequest']);
        $this->call('make:view', ['name' => $this->path . '/create']);
        $this->call('make:route', [
            'link'       => $this->path . '/create',
            'controller' => $controller,
            '--name'     => $this->path->replace('/', '.') . '.create',
        ]);
        $this->call('make:route', [
            'link'       => $this->path . '/create',
            'controller' => $controller,
            'action'     => 'store',
            '--post'     => true,
        ]);
    }

    /**
     * @return void
     */
    protected function createActionRead(): void
    {
        $controller = $this->namespace . '/ReadController';

        $this->call('make:controller', [
            'name'    => $controller,
            '--model' => $this->model,
            '--stub'  => 'stubs/controller.model.read.stub',
        ]);
        $this->call('make:view', ['name' => $this->path . '/index']);
        $this->call('make:view', ['name' => $this->path . '/details']);
        $this->call('make:route', [
            'link'       => $this->path,
            'controller' => $controller,
            '--name'     => $this->path->replace('/', '.') . '.index',
        ]);
        $this->call('make:route', [
            'link'       => $this->path . '/{' . $this->modelClass->lower() . '}',
            'controller' => $controller,
            'action'     => 'details',
        ]);
    }

    /**
     * @return void
     */
    protected function createActionUpdate(): void
    {
        $controller = $this->namespace . '/UpdateController';
        $link = $this->path . '/{' . $this->modelClass->lower() . '}/update';

        $this->call('make:controller', [
            'name'    => $controller,
            '--model' => $this->model,
            '--stub'  => 'stubs/controller.model.update.stub',
        ]);
        $this->call('make:view', ['name' => $this->path . '/update']);
        $this->call('make:route', [
            'link'       => $link,
            'controller' => $controller,
            '--name'     => $this->path->replace('/', '.') . '.update',
        ]);
        $this->call('make:route', [
            'link'       => $link,
            'controller' => $controller,
            'action'     => 'store',
            '--post'     => true,
        ]);
    }

    /**
     * @return void
     */
    protected function createActionDelete(): void
    {
        $controller = $this->namespace . '/DeleteController';
        $link = $this->path . '/{' . $this->modelClass->lower() . '}/delete';

        $this->call('make:controller', [
            'name'    => $controller,
            '--model' => $this->model,
            '--stub'  => 'stubs/controller.model.delete.stub',
        ]);
        $this->call('make:route', [
            'link'       => $link,
            'controller' => $controller,
            '--name'     => $this->path->replace('/', '.') . '.delete',
            '--post'     => true,
        ]);
    }
}
