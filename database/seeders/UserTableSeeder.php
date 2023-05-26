<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Taufit hidayat',
            'email' => 'lgrtaufit@gmail.com',
            'password' => bcrypt('Tauf1t23')
        ]);
    }
}
