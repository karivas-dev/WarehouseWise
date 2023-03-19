<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

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

        return Inertia::render('Products/Index', [
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
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validatedProduct());

        $product->categories()->attach($request->validatedCategoriesId());

        $product->warehouses()->attach(Auth::user()->warehouse->id, $request->validatedQuantity());

        return to_route('products.show', $product->id)->with([
            'type' => 'floating',
            'message' => 'Product created successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product->makeVisible('description')->append('quantity')->load('warehouses'),
            'available' => $product->warehouses->where('id', Auth::user()->warehouse->id)->where('pivot.quantity', '>', 0)->first()!=null,
            'warehouse_id' => Auth::user()->warehouse->id,
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
    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->validatedProduct());
        $product->save();

        $product->categories()->sync($request->validatedCategoriesId());

        if($product->warehouses->where('id', Auth::user()->warehouse->id)->first()!=null)
            $product->warehouses()->updateExistingPivot(Auth::user()->warehouse->id, $request->validatedQuantity());
        else if ($request->validatedQuantity()['quantity'] > 0)
            $product->warehouses()->attach(Auth::user()->warehouse->id, $request->validatedQuantity());

        return to_route('products.show', $product->id)->with([
            'type' => 'floating',
            'message' => 'Product updated',
            'level' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->warehouses()->sync([]);
        $product->delete();
        return to_route('products.index')->with([
            'type' => 'floating',
            'message' => 'Product deleted successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Remove a warehouse from specified resource.
     */
    public function remove(Product $product)
    {
        $product->warehouses()->detach(Auth::user()->warehouse->id);
        return back()->with([
            'type' => 'floating',
            'message' => 'Product removed from this warehouse',
            'level' => 'success'
        ]);
    }
}
