<?php

use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\LoginController;
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

Route::get('/',[LoginController::class, 'loginForm'])->name('login');
Route::post('logingin',[LoginController::class, 'logingIn'])->name('logingin');
Route::post('register',[LoginController::class, 'userRegister'])->name('userregister');


Route::middleware(['preventback', 'auth'])->prefix('inventory')->name('inventory.')->group(function() {
    Route::get('dashbaord',[InventoryController::class, 'dashboard'])->name('dashboard');

    //Category
    Route::get('category', [CategoryController::class, 'category'])->name('category');
    Route::post('add-category',[CategoryController::class, 'addCategory'])->name('addcategory');
    Route::post('udpate-category',[CategoryController::class, 'updateCategory'])->name('updatecategory');
    Route::post('delete-category',[CategoryController::class, 'deleteCategory'])->name('deletecategory');
    Route::post('restore-category',[CategoryController::class, 'restoreCategory'])->name('restorecategory');
    Route::post('force-delete-category',[CategoryController::class, 'forceDeleteCategory'])->name('forcedeletecategory');
    Route::get('trashed-categories',[CategoryController::class, 'trashedCategories'])->name('trashedcategories');
    Route::get('category-products/{id}',[CategoryController::class, 'categoryProducts'])->name('categoryproducts');
    Route::get('category-csv',[CategoryController::class, 'categoryCsv'])->name('categorycsv');


    //Product
    Route::get('product', [ProductController::class, 'product'])->name('product');
    Route::post('add-product', [ProductController::class, 'addProduct'])->name('addproduct');
    Route::post('udpate-product',[ProductController::class, 'updateProduct'])->name('updateproduct');
    Route::post('manage-stock',[ProductController::class, 'manageStock'])->name('managestock');
    Route::post('delete-product',[ProductController::class, 'deleteProduct'])->name('deleteproduct');
    Route::post('restore-product',[ProductController::class, 'restoreProduct'])->name('restoreproduct');
    Route::post('force-delete-product',[ProductController::class, 'forceDeleteProduct'])->name('forcedeleteproduct');
    Route::get('trashed-products',[ProductController::class, 'trashedProducts'])->name('trashedproducts');
    Route::get('product-csv',[ProductController::class, 'productCsv'])->name('productcsv');


    Route::get('logout',[LoginController::class, 'logoutUser'])->name('logout');
});
