<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * 
 * PUBLIC ROUTES
 * 
 **/

// essential middlewares
Route::group(['middleware' => ['cors', 'json.response']], function () {

    // USER
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);

    //PRODUCT
    Route::get('/products', [ProductController::class, 'index']);

    /**
     * 
     * PRIVATE ROUTES
     * 
     **/

    Route::group(['middleware' => ['auth:sanctum']], function () {


        // USER
        Route::post('logout', [UserController::class, 'logout']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/users/{id}', [UserController::class, 'update']);
        Route::get('/users/{id}', [UserController::class, 'show']);

        //WISHLIST
        Route::post('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist/add', [WishlistController::class, 'store']);
        Route::post('/wishlist/{id}', [WishlistController::class, 'show']);
        Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);

        //PRODUCT
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        //ORDERS
        Route::get('/orders', [OrdersController::class, 'index']);
        Route::post('/orders', [OrdersController::class, 'store']);
        Route::post('/orders/{id}', [OrdersController::class, 'update']);
        Route::delete('/orders/{id}', [OrdersController::class, 'destroy']);

    });
});
