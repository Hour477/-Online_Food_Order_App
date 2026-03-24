<?php

use App\Models\MenuItem;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Event;
use App\Events\ProductLiked;
use App\Events\ProductUnliked;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = Category::create(['name' => 'Test Category']);
    $this->user = User::factory()->create();
    $this->product = MenuItem::create([
        'name' => 'Test Product',
        'price' => 10.00,
        'category_id' => $this->category->id,
        'status' => 'available'
    ]);
});

test('unauthenticated user cannot like a product', function () {
    $response = $this->postJson("/api/products/{$this->product->id}/like");
    $response->assertStatus(401);
});

test('authenticated user can like a product', function () {
    Event::fake();
    
    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson("/api/products/{$this->product->id}/like");

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Product liked successfully')
             ->assertJsonPath('likes_count', 1);

    expect($this->product->isLikedBy($this->user))->toBeTrue();
    Event::assertDispatched(ProductLiked::class);
});

test('authenticated user can unlike a product', function () {
    Event::fake();
    $this->product->like($this->user);

    $response = $this->actingAs($this->user, 'sanctum')
                     ->deleteJson("/api/products/{$this->product->id}/unlike");

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Product unliked successfully')
             ->assertJsonPath('likes_count', 0);

    expect($this->product->isLikedBy($this->user))->toBeFalse();
    Event::assertDispatched(ProductUnliked::class);
});

test('user cannot like an unavailable product', function () {
    $unavailableProduct = MenuItem::create([
        'name' => 'Unavailable',
        'price' => 10.00,
        'category_id' => $this->category->id,
        'status' => 'unavailable'
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson("/api/products/{$unavailableProduct->id}/like");

    $response->assertStatus(403);
});

test('user can retrieve liked products', function () {
    $this->product->like($this->user);

    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson("/api/user/liked-products");

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonPath('data.0.id', $this->product->id);
});

test('likes count is cached in redis', function () {
    $cacheKey = "likes_count:App\Models\MenuItem:{$this->product->id}";
    
    Cache::shouldReceive('remember')
        ->once()
        ->with($cacheKey, \Mockery::any(), \Mockery::any())
        ->andReturn(0);

    $count = $this->product->likes_count;
});
