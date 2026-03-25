<?php

namespace App\Services;

use App\Helpers\ImageHelper;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingServices
{




    public function editSettings()
    {
        // Fetch all settings as key => value pairs
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    public function updateSetting(Setting $setting, array $data, $files = [])
    {
        try {
            // Handle regular fields
            $keys = [
                'resturant_name',
                'resturant_phone',
                'resturant_email',
                'resturant_address',
                'website_name',
                'app_name',
                'app_version',
                'legal_policies',
            ];

            foreach ($keys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => (string) ($data[$key] ?? '')]
                    );
                }
            }

            // Handle images
            $imageKeys = ['logo', 'favicon', 'app_logo'];
            foreach ($imageKeys as $key) {
                if (!empty($data[$key]) && $data[$key] instanceof \Illuminate\Http\UploadedFile) {
                    $oldImage = Setting::where('key', $key)->value('value');
                    $path = ImageHelper::update($data[$key], $oldImage, 'settings');
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $path]
                    );
                }
            }

            return true;
        } catch (\Throwable $th) {
            report($th);
            return false;
        }
    }
    public function storeSetting(array $data, $files = [])
    {
        
    }
}
