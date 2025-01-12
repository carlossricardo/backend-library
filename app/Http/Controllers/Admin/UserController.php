<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller {

    protected $userService;

    public function __construct( UserService $userService ){
        $this->userService = $userService;
    }

    public function findAll( Request $request ){        
        $limit = $request->get('limit', 10); 
        $offset = $request->get('offset', 0);
        return $this->userService->findAll( $offset, $limit );
    }

    public function deleteItem( Request $request ){
        return $this->userService->deleteItem( $request );
    }

    public function patch( Request $request ){
        $newRequest = collect($request)->all();       
        $user_id = $request->get('user_id');                    
        return $this->userService->patch( $user_id, $newRequest );
    }


    
}
