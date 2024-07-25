<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::disableForeignKeyConstraints();
        // User::truncate();
        // Schema::enableForeignKeyConstraints();

        User::create([
            'name' => 'Ferdinand Manager',
            'email' => 'fkoryanto@gmail.com',
            'password' => 'ferdi123',
            'role_id' => 4
        ]);
    }
}
