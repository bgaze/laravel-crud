<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;

class RegisterRoutes extends Task
{


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
        if (!$this->fs->exists($this->file())) {
            return sprintf('route file \'%s\' doesnt exists.', $this->relativePath($this->file()));
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
        $path = $this->relativePath($this->file());
        return "<info>Register routes into:</info> {$path}";
    }


    /**
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }


    /**
     * Get task's effect summary into console.
     *
     * @return string
     */
    public function done()
    {
        $path = $this->relativePath($this->file());
        return " <info>Registered routes into:</info> {$path}";
    }

}