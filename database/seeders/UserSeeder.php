<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // make user manager
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::insert([
            'name' => 'manager',
            'email' => 'manager@mail.com',
            'password' => Hash::make('$Valeuqio123'),
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
