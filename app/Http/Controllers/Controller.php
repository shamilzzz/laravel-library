<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\QueryHelper;

abstract class Controller
{
    use ApiResponse;
    use QueryHelper;
}