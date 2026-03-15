<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $search = request("search");
        $users = User::where("name","like","%".$search."%")->paginate(10);
        
        return view("admin.users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("admin.users.create");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([

            'name'  => ['required', 'string', 'max:255'],   
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            // 'role' => 'required|in:admin,waiter,kitchen'

        ]);
        User::create($validated);
        return redirect()->route("admin.users.index")->with("success", "User created successfully");
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);
        return view("admin.users.show", compact("user"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::find($id);
        return view("admin.users.edit", compact("user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validate = $request->validate([
            "name"=> "required",
            "email" => "required|email",
            "role" => "required|in:admin,waiter,kitchen",
        ]);

        $user = User::find($id);
        $user->update($validate);
        return redirect()->route("admin.users.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        $user->delete();
        return redirect()->route("admin.users.index");
    }
}
