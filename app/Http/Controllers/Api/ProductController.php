<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //index
    public function index (Request $request)
    {
        $products = Product::with('category')->when($request->status, function($query) use ($request){
            $query->where('status','like', "%{$request->status}%");
        })->orderBy('favorite', 'desc')->get();
        return response()->json(['status' => 'success', 'data' =>$products], 200);
    }

    //store
    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'image' => 'required',
            'status' => 'required',
            'criteria' => 'required',
            'favorite' => 'required',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;

        $product->status = $request->status;
        $product->criteria = $request->criteria;
        $product->favorite = $request->favorite;
        $product->save();

        if ($request->file('image')){
            $image = $request->file('image');
            $image->storeAs('public/photoproducts', $product->id . '.png');
            $product->image = $product->id . '.png';
            $product->save();
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    //show
    public function show ($id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    //update
    public function update (Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->criteria = $request->criteria;
        $product->favorite = $request->favorite;
        $product->save();

        //upload image
        if($request->file('image')){
            $image = $request->file('image');
            $image->storeAs('public/photoproducts', $product->id . '.png');
            $product->image = $product->id . '.png';
            $product->save();
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    //destroy
    public function destroy ($id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['status' => 'success' , 'message' => 'Product deleted'], 200);
    }
}
