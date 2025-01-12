<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Loan;
use App\Models\LoanDetail;

class LoanCliController extends Controller
{

    protected $loanService;

    public function __construct( LoanService $loanService ){
        $this->loanService = $loanService;
    }


    public function findAllClient( Request $request ){        
        $limit = $request->get('limit', 10); 
        $offset = $request->get('offset', 0);
        return $this->loanService->findAllClient( $offset, $limit );
    }


  
    

}
