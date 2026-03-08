<?php

namespace App\Services;


use App\Helpers\UploadImageHelper;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingServices
{

    public function getSetting(Request $request)
    {
        // $settings = Setting::all()->pluck('value', 'key')->toArray();
            

    }


    public function editSettings()
    {
        // Fetch all settings as key => value pairs
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    public function updateSetting(Setting $setting, array $data, $files = [])
    {
        // updateSetting 
        $setting->update($data);
    }
    public function storeSetting(array $data, $files = [])
    {
        // get value and key show on field
        $setting_logo = Setting::where('key', 'logo')->first()->value;

        // dd($data);
        try {
     

            // Website Name
             if (!empty($data['website_name'])) {
                Setting::updateOrInsert(
                    ['key' => 'website_name'],
                    ['value' => $data['website_name']]
                );
             }
            // Logo
            if (!empty($data['logo'])) {
                Setting::updateOrInsert(
                    ['key' => 'logo'],
                    ['value' => UploadImageHelper::update('settings/', $setting_logo, 'png', $data['logo'])]
                );
            }
            // Favicon
            if (!empty($data['favicon'])) {
                Setting::updateOrInsert(
                    ['key' => 'favicon'],
                    ['value' => UploadImageHelper::update('settings/', $setting_logo, 'png', $data['favicon'])]
                );
            }
            // App Name
            if (!empty($data['app_name'])) {
                Setting::updateOrInsert(
                    ['key' => 'app_name'],
                    ['value' => $data['app_name']]
                );
            }
            // App Version
            if (!empty($data['app_version'])) {
                Setting::updateOrInsert(
                    ['key' => 'app_version'],
                    ['value' => $data['app_version']]
                );
            }
            // app logo
            if (!empty($data['app_logo'])) {
                Setting::updateOrInsert(
                    ['key' => 'app_logo'],
                    ['value' => UploadImageHelper::update('settings/', $setting_logo, 'png', $data['app_logo'])]
                );
            }
            // Legal & Policies
            if (!empty($data['legal_policies'])) {
                Setting::updateOrInsert(
                    ['key' => 'legal_policies'],
                    ['value' => $data['legal_policies']]
                );
            }

            return true;
        } catch (\Throwable $th) {
            // dd($th);
            report($th);
            return false;
        }
    }
}
