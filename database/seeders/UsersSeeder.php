<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesAdmin = Roles::where('name', 'admin')->first();

        User::create(
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role_id' => $rolesAdmin->id,
                'password' => Hash::make('password'),
            ]

        );
    }
}
