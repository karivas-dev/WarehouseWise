<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request("search");

        $products = filter_var(request("all"), FILTER_VALIDATE_BOOLEAN) ? Product::query() : Auth::user()->warehouse->products();
        $products = $products->when($search ?? false, function($query, $search){
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->with('warehouses')->paginate(15)->withQueryString();

        return Inertia::render('Products/Show', [
            'links' => $products->toArray()['links'],
            'warehouse' => Auth::user()->warehouse,
            'products' => $products->map(function($product){
                return $product->append('quantity');
            }),
            'filters' => request()->only(['search', 'all']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/CreateEdit', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product_attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'unit_price' => 'required|decimal:2|gt:0',
        ]);

        $categories_attributes = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'numeric|gt:0|decimal:0|max:255',
        ]);

        $quantity_attribute = $request->validate([
            'quantity' => 'required|decimal:0|gte:0',
        ]);

        $product = Product::create($product_attributes);

        $product->categories()->attach($categories_attributes['categories']);

        $product->warehouses()->attach(Auth::user()->warehouse->id, $quantity_attribute);

        return back()->with([
            'type' => 'success',
            'message' => 'Product created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show2', [
            'product' => $product->makeVisible('description')->append('quantity')->load('warehouses'),
            'available' => $product->warehouses->where('id', Auth::user()->warehouse->id)->where('pivot.quantity', '>', 0)->first()!=null,
            'warehouses' => $product->warehouses->where('id', '!=' ,Auth::user()->warehouse->id)->where('pivot.quantity', '>', 0),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/CreateEdit', [
            'categories' => Category::all(),
            'product' => $product->makeVisible('description')->append('quantity'),
            'selected_categories' => $product->categories()->select('id')->get()->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product_attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'unit_price' => 'required|decimal:2|gt:0',
        ]);

        $categories_attributes = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'numeric|gt:0|decimal:0|max:255',
        ]);

        $quantity_attribute = $request->validate([
            'quantity' => 'required|decimal:0|gte:0',
        ]);

        $product->fill($product_attributes);
        $product->save();

        $product->categories()->toggle($categories_attributes['categories']);

        if($product->warehouses->where('id', Auth::user()->warehouse->id)->first()!=null)
        {
            $product->warehouses()->updateExistingPivot(Auth::user()->warehouse->id, $quantity_attribute);
        }
        else if ($quantity_attribute['quantity'] > 0)
        {
            $product->warehouses()->attach(Auth::user()->warehouse->id, $quantity_attribute);
        }

        return back()->with([
            'type' => 'success',
            'message' => 'Product updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->warehouses()->sync([]);
        $product->delete();
        return back()->with([
            'type' => 'success',
            'message' => 'Product erased',
        ]);
    }

    /**
     * Remove a warehouse from specified resource.
     */
    public function remove(Product $product)
    {
        $product->warehouses()->detach(Auth::user()->warehouse->id);
        return back()->with([
            'type' => 'success',
            'message' => 'Product removed from this warehouse',
        ]);
    }
}
