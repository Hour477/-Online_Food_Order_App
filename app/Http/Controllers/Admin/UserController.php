<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UploadImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserServices;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $users = User::where("name", "like", "%" . $search . "%")->paginate(5);

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
        $this->userServices->createUser($data, $request);
        ToastMagic::success("User created successfully");
        return redirect()->route("admin.users.index");
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
    public function edit(User $user)
    {
        if (!$user) {
            ToastMagic::warning('User not found or has been deleted.');
            return redirect()->route('admin.users.index');
                
        }

        $roles = Role::all();
        return view("admin.users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsersRequest $request, string $id)
    {
        $data = $request->validated();

        // Let service handle image upload
        $this->userServices->updateUser($data, $id, $request);
        ToastMagic::success("User updated successfully");
        return redirect()->route("admin.users.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            ToastMagic::warning('User not found or has been deleted.');
            return redirect()->route('admin.users.index');
                
        }

        // Delete user image if exists
        if ($user->image) {
            try {
                $imagePath = storage_path('app/' . $user->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            } catch (\Exception $e) {
                // Log error but don't prevent user deletion
                Log::error('Failed to delete user image: ' . $e->getMessage());
            }
        }

        $user->delete();
        ToastMagic::success('Delete User successfully');
        return redirect()->route("admin.users.index");
            
    }
}
