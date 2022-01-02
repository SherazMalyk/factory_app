<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    
    Route::get('/invoice/{stock_id}', function ($stock_id) {
        return view('invoice', compact('stock_id'));
    });

                            //HomeController
    Route::post('/loadlayersDet', [App\Http\Controllers\HomeController::class, 'loadlayersDet']);
    Route::post('/invoiceData', [App\Http\Controllers\HomeController::class, 'invoiceData']);
    Route::post('/newTable', [App\Http\Controllers\HomeController::class, 'newTable']);

    
    
    //LayerController
    Route::get('/layer', [App\Http\Controllers\LayerController::class, 'index']);
    Route::post('/LayerController', [App\Http\Controllers\LayerController::class, 'store']);
    Route::post('/loadModel', [App\Http\Controllers\LayerController::class, 'show']);
    Route::post('/editLayer', [App\Http\Controllers\LayerController::class, 'edit']);
    Route::post('/updateLayer/{id}', [App\Http\Controllers\LayerController::class, 'update']);
    Route::post('/deleteLayer', [App\Http\Controllers\LayerController::class, 'destroy']);
    
    //ProductController                    
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/editPDetails/{product_id}', [App\Http\Controllers\ProductController::class, 'edit']);
    Route::post('/editProduct', [App\Http\Controllers\ProductController::class, 'edit2']);
    Route::post('/ProductController', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/loadPDetails', [App\Http\Controllers\ProductController::class, 'show']);
    Route::post('/updateProduct/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::post('/deleteProduct', [App\Http\Controllers\ProductController::class, 'destroy']);
    Route::post('/loadLayerInS2', [App\Http\Controllers\ProductController::class, 'loadLayerInS2']);
    Route::post('/loadPartInS2', [App\Http\Controllers\ProductController::class, 'loadPartInS2']);
    
    //PartController
    Route::get('/part', [App\Http\Controllers\PartController::class, 'index']);
    Route::post('/loadPartS2', [App\Http\Controllers\PartController::class, 'loadPartS2']);
    Route::post('/PartController', [App\Http\Controllers\PartController::class, 'store']);
    Route::post('/loadPart', [App\Http\Controllers\PartController::class, 'show']);
    Route::post('/editPart', [App\Http\Controllers\PartController::class, 'edit']);
    Route::post('/updatePart/{id}', [App\Http\Controllers\PartController::class, 'update']);
    Route::post('/deletePart', [App\Http\Controllers\PartController::class, 'destroy']);
    
    Route::get('/productDetail', [App\Http\Controllers\ProductDetailController::class, 'index']);
    
    //StockController
    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index']);
    Route::post('/stockLoadProductS2', [App\Http\Controllers\StockController::class, 'stockLoadProductS2']);
    Route::post('/stockLoadPartS2', [App\Http\Controllers\StockController::class, 'stockLoadPartS2']);
    Route::post('/StockloadLayerS2', [App\Http\Controllers\StockController::class, 'StockloadLayerS2']);
    Route::post('/getWeight', [App\Http\Controllers\StockController::class, 'getWeight']);
    Route::post('/addStock', [App\Http\Controllers\StockController::class, 'store']);
    Route::post('/loadStock', [App\Http\Controllers\StockController::class, 'show']);
    Route::post('/editStock', [App\Http\Controllers\StockController::class, 'edit']);
    Route::post('/updateStock/{id}', [App\Http\Controllers\StockController::class, 'update']);
    Route::post('/deleteStock', [App\Http\Controllers\StockController::class, 'destroy']);
    
    //UserController
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/addUser', [App\Http\Controllers\UserController::class, 'store']);
    Route::post('/loadUser', [App\Http\Controllers\UserController::class, 'show']);
    Route::post('/editUser', [App\Http\Controllers\UserController::class, 'edit']);
    Route::post('/updateUser/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::post('/deleteUser', [App\Http\Controllers\UserController::class, 'destroy']);
    
    //PurchaseController
    Route::get('/purchase', [App\Http\Controllers\PurchaseController::class, 'index']);
    Route::post('/PurcLoadSupplierS2', [App\Http\Controllers\PurchaseController::class, 'PurcLoadSupplierS2']);
    Route::post('/PurcLoadProductS2', [App\Http\Controllers\PurchaseController::class, 'PurcLoadProductS2']);
    Route::post('/addPurchase', [App\Http\Controllers\PurchaseController::class, 'store']);
    Route::post('/loadPurchase', [App\Http\Controllers\PurchaseController::class, 'show']);
    Route::post('/editPurchase', [App\Http\Controllers\PurchaseController::class, 'edit']);
    Route::post('/updatePurchase/{id}', [App\Http\Controllers\PurchaseController::class, 'update']);
    Route::post('/deletePurchase', [App\Http\Controllers\PurchaseController::class, 'destroy']);
    Route::post('/purchaseSupAcc', [App\Http\Controllers\PurchaseController::class, 'purchaseSupAcc']);
    Route::post('/purSuppPrevD', [App\Http\Controllers\PurchaseController::class, 'purSuppPrevD']);
    
    
    //AccountController
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index']);
    Route::post('/AccLoadUserS2', [App\Http\Controllers\AccountController::class, 'AccLoadUserS2']);
    Route::post('/addAccount', [App\Http\Controllers\AccountController::class, 'store']);
    Route::post('/loadAccount', [App\Http\Controllers\AccountController::class, 'show']);
    Route::post('/editAccount', [App\Http\Controllers\AccountController::class, 'edit']);
    Route::post('/updateAccount/{id}', [App\Http\Controllers\AccountController::class, 'update']);
    Route::post('/deleteAccount', [App\Http\Controllers\AccountController::class, 'destroy']);
    
    //SaleController
    Route::get('/sale', [App\Http\Controllers\SaleController::class, 'index']);
    Route::post('/saleTable', [App\Http\Controllers\SaleController::class, 'show']);
    Route::post('/SaleLoadProductS2', [App\Http\Controllers\SaleController::class, 'SaleLoadProductS2']);
    Route::post('/SaleLoadCustomerS2', [App\Http\Controllers\SaleController::class, 'SaleLoadCustomerS2']);
    Route::post('/getR2Sproducts', [App\Http\Controllers\SaleController::class, 'getR2Sproducts']);
    Route::post('/saleCustPrevD', [App\Http\Controllers\SaleController::class, 'saleCustPrevD']);
    Route::post('/saleCustomerAcc', [App\Http\Controllers\SaleController::class, 'saleCustomerAcc']);
    Route::post('/addSale', [App\Http\Controllers\SaleController::class, 'store']);


});



