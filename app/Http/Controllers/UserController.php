<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');

        $users = User::when($search ?? false, function($query, $search){
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->with('warehouse')->paginate(15)->withQueryString();

        return Inertia::render('Users/Index', [
            'links' => $users->toArray()['links'],
            'users' => $users->toArray()['data'],
            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/CreateEdit', [
            'roles' => Role::all(),
            'warehouses' => Warehouse::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        Product::create($request->validated());
        return back()->with([
            'type' => 'success',
            'message' => 'User created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
