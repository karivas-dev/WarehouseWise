<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request("search");
        $warehouses = Warehouse::query()->when($search ?? false, function ($query, $search) {
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->paginate(15)->withQueryString();

        return Inertia::render('Warehouses/Index', [
            'warehouses' => $warehouses->toArray()['data'],
            'links' => $warehouses->toArray()['links'],
            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Warehouses/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated());

        return to_route('warehouses.show', $warehouse->id)->with([
            'type' => 'floating',
            'message' => 'Warehouse created successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        return Inertia::render('Warehouses/Show', [
            'warehouse' => $warehouse,
            'staff' => $warehouse->users->load(['role']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return Inertia::render('Warehouses/CreateEdit', [
            'warehouse' => $warehouse
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->fill($request->validated());
        $warehouse->save();

        return to_route('warehouses.show', $warehouse->id)->with([
            'type' => 'floating',
            'message' => 'Warehouse updated',
            'level' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return to_route('warehouses.index')->with([
            'type' => 'floating',
            'message' => 'Warehouse disable successfully',
            'level' => 'success'
        ]);
    }
}