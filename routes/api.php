<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Client\AuthenticationCliController;
use App\Http\Controllers\Admin\{SecurityController, LoanController, DashboardController, UserController};
use App\Http\Controllers\Client\CategoryCliController;
use App\Http\Controllers\Client\BookCliController;
use App\Http\Controllers\Tools\ToolController;
use App\Http\Controllers\Client\{LoanCliController, CartCliController};
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\ProfileMiddleware;


//Authentication
Route::prefix('authentication')->group(function () {

    //Client
    Route::post('/client/login', [AuthenticationCliController::class, 'loginClient']);
    Route::post('/client/register', [AuthenticationCliController::class, 'registerClient']);
});

//Dashboard
Route::prefix('dashboard')->middleware('jwt-middleware')->group(function () {

    Route::get('/active', [DashboardController::class, 'findAll'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/client/active', [DashboardController::class, 'findAllClient'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');


});



//Administration
Route::prefix('administration')->middleware('jwt-middleware')->group(function () {

    //Categories
    Route::post('/category', [CategoryController::class, 'create'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/category', [CategoryController::class, 'findAll'])
        ->middleware('profile-middleware:ADMIN');
    Route::patch('/category', [CategoryController::class, 'patch'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/category/remove', [CategoryController::class, 'deleteItem'])
        ->middleware('profile-middleware:ADMIN');


        
    Route::get('/client/category', [CategoryCliController::class, 'findAllClient'])
    ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');
        // ->middleware(ProfileMiddleware::class.':ADMIN,STUDENT,TEACHER');


    //Books
    Route::post('/book', [BookController::class, 'create'])
        ->middleware('profile-middleware:ADMIN');
    Route::patch('/book', [BookController::class, 'patch'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/book', [BookController::class, 'findAll'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/book/remove', [BookController::class, 'deleteItem'])
        ->middleware('profile-middleware:ADMIN');

    //Books clients
    Route::get('/client/book/{categoryUuid}', [BookCliController::class, 'findAllClientByCategoryUuid'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');

    Route::get('/client/book', [BookCliController::class, 'findAllClient'])
    ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  

    //Cart
    Route::get('/client/cart/books', [CartCliController::class, 'findAllCart'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  
    Route::get('/client/cart/book/add', [CartCliController::class, 'addBooksToCart'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  
    Route::get('/client/cart/book/reduce', [CartCliController::class, 'reduceBooksToCart'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  
    Route::get('/client/cart/book/remove', [CartCliController::class, 'deleteItemCartBook'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  
    Route::get('/client/cart/book/removeAll', [CartCliController::class, 'deleteAllCart'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  
    Route::post('/client/cart/book', [CartCliController::class, 'createLoanCart'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');  


    //Loans
    Route::get('/loan', [LoanController::class, 'findAll'])
        ->middleware('profile-middleware:ADMIN');
    Route::patch('/loan', [LoanController::class, 'patch'])
        ->middleware('profile-middleware:ADMIN');    
    Route::get('/loan/remove', [LoanController::class, 'deleteItem'])
        ->middleware('profile-middleware:ADMIN');

    Route::get('/client/loan', [LoanCliController::class, 'findAllClient'])
        ->middleware('profile-middleware:TEACHER,STUDENT');  



});




//Security
Route::prefix('security')->middleware('jwt-middleware')->group(function () {

    Route::post('/profile', [SecurityController::class, 'createProfile'])
        ->middleware('profile-middleware:ADMIN');

    Route::get('/user/active', [SecurityController::class, 'findUserActive'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');

    Route::patch('/user/active', [SecurityController::class, 'patch'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');

    Route::get('/checkStatus', [SecurityController::class, 'checkAuthStatus'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');


    Route::post('/privilege', [SecurityController::class, 'createPrivilege'])
        ->middleware('profile-middleware:ADMIN');





    Route::get('/user', [UserController::class, 'findAll'])
        ->middleware('profile-middleware:ADMIN');
    Route::get('/user/remove', [UserController::class, 'deleteItem'])
        ->middleware('profile-middleware:ADMIN');
    Route::patch('/user', [UserController::class, 'patch'])
        ->middleware('profile-middleware:ADMIN');

});

//Permission
Route::prefix('permission')->middleware('jwt-middleware')->group(function () {


    Route::get('/option', [SecurityController::class, 'findAllPermissions'])
        ->middleware('profile-middleware:TEACHER,STUDENT,ADMIN');          
});



Route::post('upload', [ToolController::class, 'upload']);

Route::get('files/getFile/{folder}/{file}', [ToolController::class, 'getFile']);


