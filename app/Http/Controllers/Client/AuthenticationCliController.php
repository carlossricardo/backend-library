<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthenticationService;

class AuthenticationCliController extends Controller
{
    protected $authService;

    public function __construct( AuthenticationService $authService ){
        $this->authService = $authService;
    }

    public function loginClient ( Request $request ){        
        $data_user = collect($request)->all();        
        return $this->authService->login( $data_user['email'], $data_user['password'] );
    }

    public function registerClient ( Request $request ){                
        return $this->authService->registerClient( $request );
    }
}
