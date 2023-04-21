<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class BaseController extends Controller
{
     public function __construct()
    {
        //its just a dummy data object.
       

        // // Sharing is caring
        // View::share('a', $a);
    }
}
