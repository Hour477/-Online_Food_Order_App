<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    //
    public function index()
    {
        $searrch = Role::orderBy("name")->get();
        $roles = Role::select('id','name','slug','description', 'created_at','updated_at')->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        //
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        //
    }

    public function update(Request $request, Role $role)
    {
        //
    }

    public function destroy(Role $role)
    {
        //
    }

}
