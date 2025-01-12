<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthenticationService;

class AuthenticationController extends Controller
{

    protected $authService;

    public function __construct( AuthenticationService $authService ){
        $this->authService = $authService;
    }

}
