<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustImageController extends ThrustFileController
{
    protected $blade        = "editImage";
    protected $inputName    = "image";
}