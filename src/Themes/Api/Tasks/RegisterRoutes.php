<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Api\Crud;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class RegisterRoutes extends Task
{

    /**
     * Whether the routes are already registered.
     *
     * @var bool
     */
    protected $registered;


    /**
     * Task constructor.
     *
     * @param  Crud  $crud
     * @throws FileNotFoundException
     */
    public function __construct(Crud $crud)
    {
        parent::__construct($crud);
        $this->registered = $this->alreadyRegistered();
    }


    /**
     * Check if routes are already registered.
     * @return bool
     * @throws FileNotFoundException
     */
    protected function alreadyRegistered()
    {
        return (strpos($this->fs->get($this->file()), $this->crud->ModelFullName . 'Controller') !== false);
    }


    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return base_path('routes/api.php');
    }


    /**
     * Check if something prevents the task to be executed.
     *
     * @return false|string
     */
    public function cantBeDone()
    {
        if (!$this->file_exists) {
            return sprintf('route file \'%s\' doesnt exists.', Helpers::relativePath($this->file()));
        }

        if ($this->registered) {
            return sprintf('some routes are already registered for \'%sController\'.', $this->crud->ModelFullName);
        }

        return false;
    }


    /**
     * Get task's action summary into console.
     *
     * @return string
     */
    public function summarize()
    {
        $path = Helpers::relativePath($this->file());

        if (!$this->file_exists) {
            return "<fg=red>Create:</> {$path}";
        }

        if ($this->registered) {
            return "<fg=red>Register routes into:</> {$path}";
        }

        return "<info>Register routes into:</info> {$path}";
    }


    /**
     * Execute task.
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        // Populate stub.
        if ($this->crud->getCommand()->config('expand-routes', false)) {
            $stub = $this->populateStub('routes-expanded');
        } else {
            $stub = $this->populateStub('routes-compact', [
                '#OPTIONS' => $this->crud->RoutesAlias ? ", ['as' => '{$this->crud->RoutesAlias}']" : ''
            ]);
        }

        // Create file if needed.
        if (!$this->file_exists) {
            $this->fs->put($this->file(), "<?php");
        }

        // Register routes.
        $this->fs->append($this->file(), "\n\n" . $stub);
    }


    /**
     * Get task's effect summary into console.
     *
     * @return string
     */
    public function done()
    {
        $path = Helpers::relativePath($this->file());

        if (!$this->file_exists) {
            return " <fg=red>Created:</> {$path}";
        }

        if ($this->registered) {
            return " <fg=red>Registered routes into:</> {$path}";
        }

        return " <info>Registered routes into:</info> {$path}";
    }

}