<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            [
                'email' => 'nazaryanhayk1996@gmail.com',
            ],
            [
                'name' => 'Hayk Nazaryan',
                'email' => 'nazaryanhayk1996@gmail.com',
                'password' => Hash::make(11111111)
            ]
        );

        $user->settings()->create();
    }
}
