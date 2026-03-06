<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    $table = \App\Models\Table::first();
    $user = \App\Models\User::first();
    
    if(!$table) { 
        echo "No tables found\n"; 
        exit(1); 
    }
    if(!$user) { 
        echo "No users found\n"; 
        exit(1); 
    }
    
    $order = \App\Models\Order::create([
        'order_no' => 'TEST-' . date('Ymd') . '-' . substr(md5(time()), 0, 4),
        'table_id' => $table->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
    
    echo "Order created successfully: ID = " . $order->id . "\n";
    
    // Delete the test record
    $order->delete();
    echo "Test order deleted\n";
} catch(\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
