<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Customer::insert([
            ['name' => 'Donal', 'phone' => '098765432'],
            ['name' => 'John', 'phone' => '012345678'],
        ]);
    }
}
