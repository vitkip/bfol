<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@bfol.la'],
            [
                'username'     => 'superadmin',
                'password'     => Hash::make('Admin@BFOL2026!'),
                'full_name_lo' => 'ຜູ້ດູແລລະບົບ',
                'full_name_en' => 'System Administrator',
                'full_name_zh' => '系统管理员',
                'role'         => 'superadmin',
                'is_active'    => true,
            ]
        );

        $superadmin->assignRole('superadmin');
    }
}
