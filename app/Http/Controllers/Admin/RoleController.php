<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index()
    {
        $roles = Role::select('id','name','slug','description', 'created_at','updated_at')
            ->orderBy('name')
            ->paginate(10);

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
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'required|string|max:255',

        ]); 
        Role::create($request->all());
        
        ToastMagic::success('Role created successfully');
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        //
    }

    public function edit($id)
    {
        //
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        //
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $id,
            'description' => 'required|string|max:255',

        ]); 
        $role->update($request->all());
        ToastMagic::success('Role updated successfully');
        return redirect()->route('admin.roles.index');
    }

        public function destroy($id)
        {
            $role = Role::findOrFail($id);

            try {
                // Prevent deleting admin role
                if ($role->slug === 'admin') {
                    ToastMagic::warning('Admin role cannot be deleted');
                    return redirect()->route('admin.roles.index');
                }

                // Delete role
                $role->delete();

                ToastMagic::success('Role deleted successfully');

            } catch (\Exception $e) {
                ToastMagic::error('Role deletion failed: ' . $e->getMessage());
            }

            return redirect()->route('admin.roles.index');
        }

}
