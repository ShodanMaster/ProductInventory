<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function dashboard(){
        $productCount = Product::count();
        $underStock = Product::where('quantity','<',5)->count();
        $stockValue = Product::sum(DB::raw('price * quantity'));
        return view('inventory.dashboard', compact('productCount','stockValue','underStock'));
    }
}
