<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;


class CartCliController extends Controller
{

    protected $cartService;

    public function __construct( CartService $cartService ){
        $this->cartService = $cartService;
    }

    public function findAllCart( Request $request ){
        return $this->cartService->findAllCart( $request );
    }

    public function deleteAllCart( Request $request ){
        return $this->cartService->deleteAllCart( $request );
    }

    public function addBooksToCart( Request $request ){
        return $this->cartService->addBooksToCart( $request );
    }

    public function reduceBooksToCart( Request $request ){
        return $this->cartService->reduceBooksToCart( $request );
    }

    public function deleteItemCartBook( Request $request ){
        return $this->cartService->deleteItemCartBook( $request );
    }
    public function createLoanCart( Request $request ){
        return $this->cartService->createLoanCart( $request );
    }


}
