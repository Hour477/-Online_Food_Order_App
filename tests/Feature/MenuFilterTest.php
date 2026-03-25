<?php

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category1 = Category::create(['name' => 'Pizza', 'status' => 1]);
    $this->category2 = Category::create(['name' => 'Burger', 'status' => 1]);

    MenuItem::create([
        'name' => 'Margherita Pizza',
        'price' => 10.00,
        'category_id' => $this->category1->id,
        'rating' => 4.8,
        'popularity' => 100,
        'status' => 'available'
    ]);

    MenuItem::create([
        'name' => 'Beef Burger',
        'price' => 15.00,
        'category_id' => $this->category2->id,
        'rating' => 4.2,
        'popularity' => 50,
        'status' => 'available'
    ]);

    MenuItem::create([
        'name' => 'Veggie Pizza',
        'price' => 12.00,
        'category_id' => $this->category1->id,
        'rating' => 4.6,
        'popularity' => 30,
        'status' => 'available'
    ]);
});

test('it can filter by search term', function () {
    $response = $this->getJson('/menu?search=Burger', ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
             ->assertJsonPath('pagination.total', 1);
    expect($response->json('html'))->toContain('Beef Burger');
});

test('it can filter by multiple categories', function () {
    $response = $this->getJson("/menu?cuisines={$this->category2->id}", ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
             ->assertJsonPath('pagination.total', 1);
    expect($response->json('html'))->toContain('Beef Burger');
});

test('it can filter by price range', function () {
    $response = $this->getJson('/menu?min_price=11&max_price=16', ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
             ->assertJsonPath('pagination.total', 2);
    expect($response->json('html'))->toContain('Beef Burger')
                                   ->toContain('Veggie Pizza');
});

test('it can filter by top rated', function () {
    $response = $this->getJson('/menu?top_rated=1', ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
             ->assertJsonPath('pagination.total', 2); // 4.8 and 4.6
    expect($response->json('html'))->toContain('Margherita Pizza')
                                   ->toContain('Veggie Pizza');
});

test('it can combine multiple filters', function () {
    $response = $this->getJson("/menu?search=Pizza&top_rated=1&max_price=11", ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
             ->assertJsonPath('pagination.total', 1);
    expect($response->json('html'))->toContain('Margherita Pizza');
});

test('it handles invalid filter values gracefully', function () {
    $response = $this->getJson('/menu?min_price=abc&sort=invalid', ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(422); // Validation error
});

test('it prevents SQL injection in search', function () {
    $injection = "' OR 1=1 --";
    $response = $this->getJson("/menu?search=" . urlencode($injection), ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    // Should return 0 if nothing matches literally
    expect($response->json('pagination.total'))->toBe(0);
});
