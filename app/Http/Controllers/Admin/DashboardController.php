<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{

    protected $dashboardService;

    public function __construct( DashboardService $dashboardService ){
        $this->dashboardService = $dashboardService;
    }


    public function findAll ( ){                      
        return $this->dashboardService->findAll();
    }

    public function findAllClient ( ){                      
        return $this->dashboardService->findAllClient();
    }


}
