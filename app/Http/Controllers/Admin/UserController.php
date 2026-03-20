<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use App\Models\Role;
use App\Http\Requests\Admin\UsersRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected UserServices $userServices;
    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    
    public function index()
    {
        
        $search = request("search");
        $users = User::where("name","like","%".$search."%")->paginate(5);
        
        return view("admin.users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::select("id", "name")->get();
        return view("admin.users.create", compact("roles"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsersRequest $request)
    {
        //
        $data = $request->validated();
        $data['image'] = $request->file('image');
        
        $this->userServices->createUser($data);
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
