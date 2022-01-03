<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    { 
        $this->validate($request, [
            'name' => 'required|max:255',
            'price' => 'required|max:7',
            'upc' => 'required|max:255',
            'image' => 'required|max:255',
        ]);

        $product         = new Product;
        $product->name   = $request['name'];
        $product->price  = $request['price'];
        $product->upc    = $request['upc'];
        $product->image  = $request['image'];
        $product->status = true;

        $product->save();

        return redirect('/products')->with('success', 'Product Saved successfully');
    }

    /**
     * Store updated resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'  => 'required|max:255',
            'price' => 'required|max:7',
            'upc'   => 'required|max:255|unique:products,upc,' . $product->id,
            'image' => 'required|max:255',
        ]);

        $product->name   = $request['name'];
        $product->price  = $request['price'];
        $product->upc    = $request['upc'];
        $product->image  = $request['image'];

        $product->update();

        return redirect('/products')->with('success', 'Product Saved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
     
    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $product = Product::find($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully');
    }   
  
     /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;
        Product::whereIn('id', explode(',', $ids))->delete();

        return response()->json(['status'=>true, 'message' => 'Product deleted successfully.']);  
    }

}
