<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $category = Category::all();
        return $category;
    }

    public function selectCategory(Request $request)
    {
        $idCategory = $request->id_category;
        $category = Product::where('category_id', $idCategory)->get();
        return $category;
    }

    public function saveCategory(Request $request)
    {
        $name = $request->name;
        $file = $request->file;

        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $result = $request->file('file')->store( 'public/photocategory');
            $url = Storage::url($result);
        }
        $category = new Category();
        $category->category_name = $name;
        $category->category_photo = $url;
        $category->save();
        return true;
    }

    public function delCategory(Request $request)
    {
        $idCategory = $request->input('idCategory');
        Category::where('id', $idCategory)->delete();
        Product::where('category_id', $idCategory)->delete();
        return true;
    }
}
