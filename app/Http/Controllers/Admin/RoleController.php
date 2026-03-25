<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class RoleController extends Controller
{
    //
    public function generateSlug(Request $request)
    {
        $name = $request->get('name');
        $slug = Str::slug($name);
        $count = Role::where('name', $name)->count();
        if ($count > 0) {
            $slug = "{$slug}";
        }

        return response()->json(['slug' => $slug]);
    }

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
        $role = Role::findOrFail($id);

        // Prevent changing core system slugs (admin, customer)
        $systemRoles = ['admin', 'customer'];
        if (in_array($role->slug, $systemRoles) && $request->slug !== $role->slug) {
            ToastMagic::warning('System role slugs cannot be modified to ensure access stability.');
            return redirect()->route('admin.roles.index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $id,
            'description' => 'nullable|string|max:255',
        ]); 
        
        $role->update($request->all());
        ToastMagic::success('Role updated successfully');
        return redirect()->route('admin.roles.index');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        try {
            // Prevent deleting core system roles (admin, customer)
            $systemRoles = ['admin', 'customer'];
            if (in_array($role->slug, $systemRoles)) {
                ToastMagic::warning("The {$role->name} role is a system role and cannot be deleted.");
                return redirect()->route('admin.roles.index');
            }

            $role->delete();
            ToastMagic::success('Role deleted successfully');

        } catch (\Exception $e) {
            ToastMagic::error('Cannot delete role. It may be assigned to users.');
        }

        return redirect()->route('admin.roles.index');
    }
}
