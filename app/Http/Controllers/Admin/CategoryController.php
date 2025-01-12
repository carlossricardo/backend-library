<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct( CategoryService $categoryService ){
        $this->categoryService = $categoryService;
    }


    public function create( Request $request ){        
        $newRequest = collect($request)->all();        
        return $this->categoryService->create( $newRequest );
    }

    public function findAll ( Request $request ){                      
        $limit = $request->get('limit', 10); 
        $offset = $request->get('offset', 0);
        return $this->categoryService->findAll( $offset, $limit );        

    }

    public function patch( Request $request ){
        $newRequest = collect($request)->all();  
        $category_id = $request->get('category_id');                       
        return $this->categoryService->patch( $category_id, $newRequest );
    }

    public function deleteItem( Request $request ){
        return $this->categoryService->deleteItem( $request );
    }

}
