<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\{BookService, CategoryService};

class CategoryCliController extends Controller
{
    protected $bookService;
    protected $categoryService;

    public function __construct( BookService $bookService, CategoryService $categoryService){
        $this->bookService = $bookService;
        $this->categoryService = $categoryService;
    }

    public function saludar()
    {
        return "Hola Mundo";
    }

    public function findAllByBookUuid( $categoryUuid ){
        return $this->bookService->findAllByBookUuid( $categoryUuid );
    }


    public function findAllClient(){
        return $this->categoryService->findAllClient();
    }

}
// 