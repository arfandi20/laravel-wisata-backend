<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{

    //index
   public function index (Request $request)
   {
    $products = Product::when($request->keyword, function($query) use ($request){
        $query->where('name', 'like', "%{$request->keyword}%")
        ->orWhere('description', 'like', "%{$request->keyword}%");
    })->orderBy('id', 'desc')->paginate(20);
    return view('pages.products.index', compact('products'));
    }

    //create
    public function create (){
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.products.create', compact('categories'));
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
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
        //image
        $image = $request->file('image');
        $image->storeAs('public/photoproducts', $product->id . '.' . $image->extension());
        $product->image = 'photoproducts/' . $product->id . '.' . $image ->extension();
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    //edit
    public function edit ($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view ('pages.products.edit', compact('product','categories'));
    }

    //update
    public function update (Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'criteria' => 'required',
            'favorite' => 'required',
        ]);

        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->criteria = $request->criteria;
        $product->favorite = $request->favorite;
        $product->save();

        if ($request->image) {
            $image = $request->file('image');
            $image->storeAs('public/photoproducts', $product->id . '.' . $image->extension());
            $product->image = 'photoproducts/' . $product->id . '.' . $image ->extension();
            $product->save();
        }


        return redirect()->route('products.index')->with('success', 'Product update successfully');
    }

    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }


}
