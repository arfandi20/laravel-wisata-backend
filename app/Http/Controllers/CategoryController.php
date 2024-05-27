<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
     //index user
     public function index (Request $request)
     {
        $categories = Category::when($request->keyword, function($query) use ($request){
            $query->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('description', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate(20);
        return view('pages/categories/index', compact('categories'));
    }

    //create
    public function create(){
        return view('pages/categories/create');
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'description'=> 'required',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Category Created Successfully');
    }

    //edit
    public function edit(Category $category){
        return view('pages/categories/edit', compact('category'));
    }

    //update
    public function update(Request $request, Category $category) {
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category Update Successfully');
    }

    //hapus
    public function destroy(Category $category){
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully');
    }

}
