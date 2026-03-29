<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class BannerController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Banner::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                }
                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            });

        $banners = $query->latest()->paginate($request->get('per_page', 10))->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title'    => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::upload($request->file('image'), 'banners');
        }

        // Set default is_active if not provided
        $validated['is_active'] = $request->has('is_active');

        Banner::create($validated);
        ToastMagic::success('Banner created successfully');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title'    => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::update($request->file('image'), $banner->image, 'banners');
        }

        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);
        ToastMagic::success('Banner updated successfully');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image from storage
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();
        ToastMagic::success('Banner deleted successfully');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Toggle banner active status.
     */
    public function toggleStatus(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        ToastMagic::success($banner->is_active ? 'Banner activated' : 'Banner deactivated');
        return redirect()->route('admin.banners.index');
    }
}
