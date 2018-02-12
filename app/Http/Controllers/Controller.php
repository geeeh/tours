<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Return the path to public dir.
     *
     * @param null $path - destination
     *
     * @return string - full path
     */
    function publicPath($path=null)
    {
            return rtrim(app()->basePath('public/'.$path), '/');
    }
}
