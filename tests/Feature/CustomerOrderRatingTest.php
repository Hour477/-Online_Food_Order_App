<?php

use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\MenuItemRating;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createRatedOrderContext(): array
{
    $user = User::factory()->create([
        'state' => 'Phnom Penh',
    ]);
    $customer = Customer::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ]);

    $menuItem = MenuItem::create([
        'name' => 'Amok Fish',
        'price' => 12.50,
        'status' => 'available',
    ]);

    $order = Order::create([
        'order_no' => 'ORD-10001',
        'order_type' => 'delivery',
        'customer_id' => $customer->id,
        'status' => 'completed',
        'subtotal' => 12.50,
        'tax' => 1.25,
        'total_amount' => 13.75,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'menu_item_id' => $menuItem->id,
        'quantity' => 1,
        'price' => 12.50,
        'subtotal' => 12.50,
    ]);

    return [$user, $customer, $menuItem, $order];
}

test('customer can rate a completed ordered menu item', function () {
    [$user, $customer, $menuItem, $order] = createRatedOrderContext();

    $response = $this->actingAs($user)->post(route('customerOrder.orders.rate'), [
        'order_id' => $order->id,
        'menu_item_id' => $menuItem->id,
        'rating' => 4,
        'comment' => 'Well cooked and balanced flavor.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('menu_item_ratings', [
        'order_id' => $order->id,
        'menu_item_id' => $menuItem->id,
        'customer_id' => $customer->id,
        'rating' => 4,
        'comment' => 'Well cooked and balanced flavor.',
    ]);

    expect((float) $menuItem->fresh()->rating)->toBe(4.0);
});

test('order history shows existing customer rating details', function () {
    [$user, $customer, $menuItem, $order] = createRatedOrderContext();

    MenuItemRating::create([
        'order_id' => $order->id,
        'menu_item_id' => $menuItem->id,
        'customer_id' => $customer->id,
        'rating' => 5,
        'comment' => 'Would order again.',
    ]);

    $menuItem->updateAverageRating();

    $response = $this->actingAs($user)->get(route('customerOrder.orders.history'));

    $response->assertOk();
    $response->assertSee('Would order again.');
    $response->assertSee('5/5');
    $response->assertSee('Edit');
});
