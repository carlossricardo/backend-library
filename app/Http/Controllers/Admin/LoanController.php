<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoanService;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct( LoanService $loanService ){
        $this->loanService = $loanService;
    }


    public function findAll( Request $request ){        
        $limit = $request->get('limit', 10); 
        $offset = $request->get('offset', 0);
        return $this->loanService->findAll( $offset, $limit );
    }

    public function patch( Request $request ){        
        return $this->loanService->patch( $request );
    }

    public function deleteItem( Request $request ){
        return $this->loanService->deleteItem( $request );
    }

}
