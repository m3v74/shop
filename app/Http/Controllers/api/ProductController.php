<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProduct(Request $request)
    {
        $products = Product::all();
        return $products;
    }
    public function saveProduct(Request $request)
    {
        $idCategory = $request->idCategory;
        $nameProduct = $request->nameProduct;
        $priceProduct = $request->priceProduct;
        $quantityProduct = $request->quantityProduct;
        $allowdDiscount = $request->allowdDiscount;
        $discountProduct = $request->discountProduct;
        $file = $request->file;

        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $result = $request->file('file')->store( 'public/photoproduct');
            $url = Storage::url($result);
        }
        $product = new Product();
        $product->category_id = $idCategory;
        $product->product_name = $nameProduct;
        $product->unit_price = $priceProduct;
        $product->quantity = $quantityProduct;
        $product->discount_allow = $allowdDiscount;
        $product->discount = $discountProduct;
        $product->product_photo = $url;
        $product->save();

        $product = Product::where('category_id', $idCategory)->orderBy('id')->get();
        return $product;
    }

    public function delProduct(Request $request)
    {
        $id = $request->input('idProduct');
        $idCategory = $request->input('idCategory');
        $product = Product::find($id);
        $product->delete();
        $product = Product::where('category_id', $idCategory)->get();
        return $product;
    }

    public function delProducts(Request $request)
    {
        $idCategory = $request->input('idCategory');
        Product::where('category_id', $idCategory)->delete();
        $product = Product::where('category_id', $idCategory)->get();
        return $product;
    }

}
