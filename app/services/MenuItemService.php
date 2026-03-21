<?php
namespace App\Services;

use App\Models\MenuItem;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Helpers\ImageHelper;

class MenuItemService
{       

    public function list(MenuItemRequest $request){

    }
    public function store(MenuItemRequest $request)
    {
        $validated = $request->validated();
        $menuItem = MenuItem::create($validated);
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
        dd($menuItem);
        $menuItem->update($data , ['id' => $id]);
        return $menuItem;
    }

    public function destroy(MenuItem $menuItem)
    {
        // Delete the menu item image if it exists
        if ($menuItem->image) {
            ImageHelper::delete($menuItem->image);
        }
        // Delete the menu item
        $menuItem->delete();
    }


}