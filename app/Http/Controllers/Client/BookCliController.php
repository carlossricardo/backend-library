<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BookService;

class BookCliController extends Controller
{
    protected $bookService;

    public function __construct( BookService $bookService ){
        $this->bookService = $bookService;
    }

    public function saludar()
    {
        return "Hola Mundo";
    }

    public function findAllClientByCategoryUuid( $categoryUuid, Request $request ){
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0); 
        return $this->bookService->findAllClientByCategoryUuid( $categoryUuid, $offset, $limit );
    }



    public function findAllClient( Request $request ){

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0); 
        return $this->bookService->findAllClient( $offset, $limit );
    }


}
