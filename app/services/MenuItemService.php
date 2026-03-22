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
        $menuItem = MenuItem::create($data);
        $menuItem->save();
        return $menuItem;
        
    }
    public function update(string $id, array $data){
        // Update the menu item
        $menuItem = MenuItem::findOrFail($id);
        if ($menuItem->update($data)) {
            if (isset($data['image'])) {
                $data['image'] = ImageHelper::update(
                    $data['image'],
                    $menuItem->image,
                    'menu-items',
                );
            }
        }
        
        $menuItem->update($data , ['id' => $id]);
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