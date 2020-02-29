<?php


namespace Bgaze\Crud\Themes\Classic\Tasks;

use Bgaze\Crud\Themes\Api\Tasks\RegisterRoutes as BaseTask;

class RegisterRoutes extends BaseTask
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return base_path('routes/web.php');
    }

}