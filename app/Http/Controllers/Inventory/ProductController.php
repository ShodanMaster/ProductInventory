<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product(){
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('inventory.product', compact('products', 'categories'));
    }

    public function addProduct(Request $request){

        $validatedData = $request->validate([
            'category_id' => 'required|string', //category_id before decrypting
            'name'        => 'required|string|max:255|unique:products,name',
            'sku'         => 'required|string|max:50|unique:products,sku',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'quantity'    => 'required|integer|min:0',
        ]);

        try{
            Product::create([
                'category_id' => decrypt($validatedData['category_id']),
                'name'        => $validatedData['name'],
                'sku'         => $validatedData['sku'],
                'price'       => $validatedData['price'],
                'quantity'    => $validatedData['quantity'],
            ]);
            return redirect()->back()->with('success', 'Product Added Successfully');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to add the product: ' . $e->getMessage()]);
        }
    }

    public function updateProduct(Request $request){

        $category = request()->category_id ? decrypt(request()->category_id) : decrypt(request()->oldcategory_id);

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255|unique:products,name,'. decrypt(request()->id),
            'sku'         => 'required|string|max:50|unique:products,sku,'. decrypt(request()->id),
            'price'       => 'required|numeric|min:0|max:999999.99',
            'quantity'    => 'required|integer|min:0',
        ]);

        try {
            $product = Product::find(decrypt(request()->id));
            if($product){

                $product->update([
                    'category_id' => $category,
                    'name' => request()->name,
                    'sku' => request()->sku,
                    'price' => request()->price,
                    'quantity' => request()->quantity,

                ]);
                return redirect()->back()->with('success','Product Updated Successfully');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function deleteProduct(){
        try{
            $product = Product::find(decrypt(request()->id));
            if($product){
                $product->delete();
                return redirect()->back()->with('success', 'Product Trashed Successfully');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to delete the product: ' . $e->getMessage()]);
        }
    }

    public function restoreProduct(){
        try{
            $product = Product::withTrashed()->find(decrypt(request()->id));
            if($product){
                $product->restore();
                return redirect()->back()->with('success', 'Product Restored Successfully');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to restore the product: ' . $e->getMessage()]);
        }
    }

    public function forceDeleteProduct(){
        try{
            $product = Product::withTrashed()->find(decrypt(request()->id));
            if($product){
                $product->forcedelete();
                return redirect()->back()->with('success', 'Product Deleted Permanently');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to delete permanently the product: ' . $e->getMessage()]);
        }
    }

    public function manageStock(){
        try{
            $product = Product::find(decrypt(request()->id));

            if($product){
                $product->update([
                    'quantity' => request()->quantity,
                ]);
                return redirect()->back()->with('success', 'Product Quantity Updated');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to update stock of the product: ' . $e->getMessage()]);
        }
    }

    public function productCsv(){
        $products = Product::latest()->get();
        $filename = "products_csv.csv";
        $fp=fopen($filename, "w+");
        fputcsv($fp, array('Name', 'Category', 'SKU', 'Price', 'Quantity'));

        foreach($products as $row){
            fputcsv($fp, array($row->name, $row->category->name, $row->sku, $row->price, $row->quantity));
        }

        fclose($fp);
        $headers = array('Content-Type' => 'text/csv');

        return response()->download($filename, 'products_csv.csv', $headers);
    }

    public function trashedProducts(){
        $products = Product::onlyTrashed()->get();
        return view('inventory.trashedProducts', compact('products'));

    }
}
