<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ToolService;

class ToolController extends Controller
{

    protected $toolService;

    public function __construct( ToolService $toolService ){
        $this->toolService = $toolService;
    }

    public function upload( Request $request ){             
        return $this->toolService->upload( $request );
    }

    public function getFile( $folder, $file ){             
        return $this->toolService->getFile( $folder, $file );
    }
}
