<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SettingServices;

use Devrabiul\ToastMagic\Facades\ToastMagic;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected SettingServices $settingServices;
    public function __construct(SettingServices $settingServices)
    {
        $this->settingServices = $settingServices;
    }
    public function index(Request $request)
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'resturant_name'],
            ['value' => '']
        );
        $settings = Setting::query()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('setting', 'settings'));
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $data = $request->validated();

        $this->settingServices->updateSetting($setting, $data);
       
        ToastMagic::success('Settings updated successfully');
        
        return redirect()->route('admin.settings.index');
    }

   
}
