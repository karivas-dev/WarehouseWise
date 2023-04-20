<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use function Termwind\render;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            'remove' => 'remove',
            'restore' => 'restore',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request("search");
        $category = request("category");
        $deleted = filter_var(request("deleted"), FILTER_VALIDATE_BOOLEAN);

        if (filter_var(request("all"), FILTER_VALIDATE_BOOLEAN) || Auth::user()->role->type == 'director') {
            $products = Product::query();
        } else {
            $products = Auth::user()->warehouse->products();
        }

        //$products = filter_var(request("all"), FILTER_VALIDATE_BOOLEAN) ? Product::query() : Auth::user()->warehouse->products();
        $products = $products->when($search ?? false, function($query, $search) {
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->when($category ?? false, function ($query, $category) {
            if ($category != 'all') {
                $query->whereRelation('categories', 'id', $category);
            }
        })->when($deleted ?? false, function ($query, $deleted) {
            if ($deleted) {
                $query->onlyTrashed();
            }
        })->with('warehouses')->paginate(15)->withQueryString();

        return Inertia::render('Products/Index', [
            'links' => $products->toArray()['links'],
            'warehouse' => Auth::user()->warehouse,
            'categories' => Category::all(),
            'products' => $products->map(function($product){
                return $product->append('quantity');
            }),
            'filters' => request()->only(['search', 'all', 'category', 'deleted']),
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

        if (Auth::user()->role->type != 'director') {
            $product->warehouses()->attach(Auth::user()->warehouse->id, $request->validatedQuantity());
        }

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
            'product' => $product->makeVisible('description')->append('quantity')->load('warehouses', 'categories'),
            'available' => Auth::user()->warehouse != null ? $product->warehouses->where('id', Auth::user()->warehouse->id)->where('pivot.quantity', '>', 0)->first()!=null : 0,
            'warehouse_id' => Auth::user()->warehouse?->id,
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

    public function restore(Product $product)
    {
        $product->restore();
        return back()->with([
            'type' => 'floating',
            'message' => 'Product restored successfully',
            'level' => 'success'
        ]);
    }

    public function addToOrder(Product $product)
    {
        return 'ADDED';
    }
}
