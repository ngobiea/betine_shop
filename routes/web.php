<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $isLogin = Session::get('isLogin');
    $products = DB::table('products')->get();
    return view('homePage', compact('products', 'isLogin'));
});


Route::get('/search', function (Request $request) {

    if ($request->ajax()) {

        $output = '';

        $products = DB::table('products')->where('title', 'LIKE', '%' . $request->search . '%')->get();

        if ($products) {

            foreach ($products as $product) {

                $output .=
                    '<div class="card-body">
                    <h5 class="card-title"><b>' . $product->title . '</b></h5>
                </div>
              ';
            }

            return response()->json($output);
        }
    }
    return view('search');
});


// Seller Routes
Route::get('/sellerSignup', [SellerController::class, 'getSignup']);
Route::post('/sellerSignup', [SellerController::class, 'postSignup']);
Route::get('/sellerLogin', [SellerController::class, 'getLogin']);
Route::post('/sellerLogin', [SellerController::class, 'postLogin']);
Route::post('/sellerLogout', [SellerController::class, 'logout']);

Route::get('/seller', [SellerController::class, 'index']);
Route::get('/addProduct', [SellerController::class, 'getAddProduct']);
Route::post('/addProduct', [SellerController::class, 'postAddProduct']);
Route::get('/updateProduct/{id}', [SellerController::class, 'getUpdateProduct']);
Route::post('/updateProduct', [SellerController::class, 'patchUpdateProduct']);
Route::delete('/deleteProduct/{id}', [SellerController::class, 'deleteProduct']);

// Buyer Routes
Route::get('/buyerSignup', [BuyerController::class, 'getSignup']);
Route::post('/buyerSignup', [BuyerController::class, 'postSignup']);
Route::get('/buyerLogin', [BuyerController::class, 'getLogin']);
Route::post('/buyerLogin', [BuyerController::class, 'postLogin']);
Route::post('/buyerLogout', [BuyerController::class, 'logout']);


Route::get('/buyer', [BuyerController::class, 'index']);
Route::get('/getProduct/{id}', [BuyerController::class, 'getProduct']);
Route::post('/addToCart', [BuyerController::class, 'addToCart']);
Route::get('/getCart', [BuyerController::class, 'getCart']);
Route::delete('/deleteCartItem/{id}', [BuyerController::class, 'deleteCartItem']);
Route::post('/addToOrder', [BuyerController::class, 'addToOrder']);
Route::get('/getOrder', [BuyerController::class, 'getOrder']);


// Admin Routes
Route::get('/adminSignup', [AdminController::class, 'getSignup']);
Route::post('/adminSignup', [AdminController::class, 'postSignup']);
Route::get('/adminLogin', [AdminController::class, 'getLogin']);
Route::post('/adminLogin', [AdminController::class, 'postLogin']);
Route::post('/adminLogout', [AdminController::class, 'logout']);
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/getSeller', [AdminController::class, 'getSeller']);
Route::delete('/deleteSeller/{id}', [AdminController::class, 'deleteSeller']);
Route::get('/getBuyer', [AdminController::class, 'getBuyer']);
Route::delete('/deleteBuyer/{id}', [AdminController::class, 'deleteBuyer']);
Route::delete('/adminDeleteProduct/{id}',[AdminController::class,'deleteProduct']);
