<?php
namespace App\Services;

use App\Models\MenuItem;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Helpers\ImageHelper;

class MenuItemService
{       

    public function list(MenuItemRequest $request){

    }
    public function store(array $data){

        if (isset($data['image'])) {
            $data['image'] = ImageHelper::upload($data['image'], 'menu-items');
        }

        // Ensure rating and popularity are not null
        $data['rating'] = $data['rating'] ?? 0;
        $data['popularity'] = $data['popularity'] ?? 0;

        $menuItem = MenuItem::create($data);
        return $menuItem;
        
    }
    public function update(string $id, array $data)
    {
        $menuItem = MenuItem::findOrFail($id);
        $oldImage = $menuItem->image;

        if (isset($data['image'])) {
            $data['image'] = ImageHelper::update(
                $data['image'],
                $oldImage,
                'menu-items'
            );
        }

        // Ensure rating and popularity are not null if they are present in the data
        if (array_key_exists('rating', $data)) {
            $data['rating'] = $data['rating'] ?? 0;
        }
        if (array_key_exists('popularity', $data)) {
            $data['popularity'] = $data['popularity'] ?? 0;
        }

        $menuItem->update($data);
        return $menuItem;
    }

    public function destroy(string $id)
    {
        // Delete the menu item image if it exists
        $menuItem = MenuItem::findOrFail($id);
        if ($menuItem->image) {
            ImageHelper::delete($menuItem->image);
        }
        // Delete the menu item
        $menuItem->delete();
        return $menuItem;
    }


}