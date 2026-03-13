<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Devrabiul\ToastMagic\Facades\ToastMagic;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Keep one record available for resource route model binding on update.
        $setting = Setting::firstOrCreate(
            ['key' => 'resturant_name'],
            ['value' => '']
        );

        $settings = Setting::query()->pluck('value', 'key')->toArray();

        return view('setttings.index', compact('settings', 'setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $data = $request->validated();

        $keys = [
            'resturant_name',
            'resturant_phone',
            'resturant_email',
            'resturant_address',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) ($data[$key] ?? '')]
                );
            }
        }

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::where('key', 'logo')->value('value');
            $fileName = \App\Helpers\UploadImageHelper::update('settings/', $oldLogo, 'png', $request->file('logo'));
            Setting::updateOrCreate(['key' => 'logo'], ['value' => $fileName]);
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::where('key', 'favicon')->value('value');
            $fileName = \App\Helpers\UploadImageHelper::update('settings/', $oldFavicon, 'png', $request->file('favicon'));
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $fileName]);
        }
        ToastMagic::success('Settings updated successfully');
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
