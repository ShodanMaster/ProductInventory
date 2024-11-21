<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function category(){
        $categories = Category::all();
        return view('inventory.category', compact('categories'));
    }

    public function addCategory(Request $request){
        $validateData = $request->validate([
            'name' => 'string|max:255|unique:categories,name'
        ]);
        try{
            Category::create([
                'name' =>$validateData['name']
            ]);
            return redirect()->back()->with('success','Category Added Successfully');
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to add the category: ' . $e->getMessage()]);
        }
    }

    public function updateCategory(Request $request){
        $validateData = $request->validate([
            'name' => 'string|max:255|unique:categories,name,'.decrypt(request()->id)
        ]);

        try{
            $category = Category::find(decrypt(request()->id));
            if($category){
                $category->update([
                    'name' => request()->name
                ]);
                return redirect()->back()->with('success','Category Updated Successfully');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to update the category: ' . $e->getMessage()]);
        }
    }

    public function deleteCategory(){
        try{
            $category = Category::find(decrypt(request()->id));
            if($category){
                $category->delete();
                return redirect()->back()->with('success', 'Category Trashed Successfully');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to delete the category: ' . $e->getMessage()]);
        }
    }

    public function restoreCategory(){
        try{
            $category = Category::withTrashed()->find(decrypt(request()->id));
            if($category){
                $category->restore();
                return redirect()->back()->with('success', 'Category Restored Successfully');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to restore the category: ' . $e->getMessage()]);
        }
    }

    public function forceDeleteCategory(){
        try{
            $category = Category::withTrashed()->find(decrypt(request()->id));
            if($category){
                $category->forcedelete();
                return redirect()->back()->with('success', 'Category Deleted Permanently');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong!');
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to delete permanently the category: ' . $e->getMessage()]);
        }
    }

    public function categoryProducts($id){
        try{
            $categories = Category::all();
            $category = Category::find(decrypt($id));
            return view('inventory.categoryProducts',compact('category', 'categories'));
        }catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to get the category: ' . $e->getMessage()]);
        }
    }

    public function trashedCategories(){
        $categories = Category::onlyTrashed()->get();
        return view('inventory.trashedCategory', compact('categories'));

    }

    public function categoryCsv(){
        $categories = Category::latest()->get();
        $filename = "categories_csv.csv";
        $fp=fopen($filename, "w+");
        fputcsv($fp, array('Name'));

        foreach($categories as $row){
            fputcsv($fp, array($row->name));
        }

        fclose($fp);
        $headers = array('Content-Type' => 'text/csv');

        return response()->download($filename, 'categories_csv.csv', $headers);
    }
}
