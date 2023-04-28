<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');

        $categories = Category::query()->when($search ?? false, function ($query, $search) {
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->paginate(15)->withQueryString();

        return Inertia::render('Categories/Index', [
            'categories' => $categories->items(),
            'links' => $categories->toArray()['links'],
            'filters' => request()->only('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return to_route('categories.show', $category->id)->with([
            'type' => 'floating',
            'message' => 'Category created successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Categories/CreateEdit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->fill($request->validated())->save();

        return to_route('categories.show', $category->id)->with([
            'type' => 'floating',
            'message' => 'Category updated successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return to_route('categories.index')->with([
            'type' => 'floating',
            'message' => 'Category deleted successfully',
            'level' => 'success'
        ]);
    }
}
