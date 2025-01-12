<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BookService;

class BookController extends Controller {

    protected $bookService;

    public function __construct( BookService $bookService ){
        $this->bookService = $bookService;
    }


    public function create( Request $request ){        
        $newRequest = collect($request)->all();        
        return $this->bookService->create( $newRequest );
    }
    
    public function patch( Request $request ){
        $newRequest = collect($request)->all();  
        $book_id = $request->get('book_id');                       
        return $this->bookService->patch( $book_id, $newRequest );
    }


    public function findAll( Request $request ){        
        $limit = $request->get('limit', 10); 
        $offset = $request->get('offset', 0);
        return $this->bookService->findAll( $offset, $limit );
    }

    public function deleteItem( Request $request ){
        return $this->bookService->deleteItem( $request );
    }


    
}
