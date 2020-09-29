<?php

declare(strict_types=1);

namespace Brnbio\LaravelExtended\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeViewCommand
 *
 * @package Brnbio\LaravelExtended\Console\Commands
 */
class MakeViewCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $name = 'make:view';

    /**
     * @var string
     */
    protected $description = 'Create a new resource view';

    /**
     * @var string
     */
    protected $type = 'View';

    /**
     * @return bool
     * @throws FileNotFoundException
     */
    public function handle(): bool
    {
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        $name = $this->getNameInput();
        $path = $this->getPath($name);

        if ($this->option('force') !== true && $this->alreadyExists($name)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($this->buildClass($name)));
        $this->info($this->type . ' created successfully.');

        return true;
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/view.stub');
    }

    /**
     * @param $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        $customPath = $this->laravel->basePath(trim($stub, '/'));

        if (file_exists($customPath)) {
            return $customPath;
        }

        return __DIR__ . $stub;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        return $this->laravel->resourcePath() . '/views/' . $name . '.blade.php';
    }

    /**
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($rawName));
    }

    /**
     * @return array[]
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
        ];
    }
}
