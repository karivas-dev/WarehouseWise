<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $deleted = filter_var(request("deleted"), FILTER_VALIDATE_BOOLEAN);

        if (filter_var(request("all"), FILTER_VALIDATE_BOOLEAN) || Auth::user()->role->type == 'director') {
            $users = User::query();
        } else {
            $users = Auth::user()->warehouse->users();
        }

        $users = $users->when($search ?? false, function($query, $search){
            $search = preg_replace("/([^A-Za-z0-9\s])+/i", "", $search);
            $query->where('name', 'LIKE', "%$search%");
        })->when($deleted ?? false, function ($query, $deleted) {
            if ($deleted) {
                $query->onlyTrashed();
            }
        })->with('warehouse', 'role')->paginate(15)->withQueryString();

        return Inertia::render('Users/Index', [
            'links' => $users->toArray()['links'],
            'users' => $users->toArray()['data'],
            'filters' => request()->only(['search', 'all', 'deleted']),
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
        $user = User::create($request->validated());
        return to_route('users.show', $user->id)->with([
            'type' => 'floating',
            'message' => 'User created successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user->load(['role', 'warehouse']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/CreateEdit', [
            'user' => $user,
            'roles' => Role::all(),
            'warehouses' => Warehouse::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->fill($request->validated());
        $user->save();
        return to_route('users.show', $user->id)->with([
            'type' => 'floating',
            'message' => 'User updated successfully',
            'level' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return to_route('users.index')->with([
            'type' => 'floating',
            'message' => 'User deleted successfully',
            'level' => 'success'
        ]);
    }
}
