<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@memoriacastrense.gob.ve');
        $password = env('ADMIN_PASSWORD');

        if (!$password) {
            return;
        }

        if (DB::table('users')->where('email', $email)->exists()) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'administrador',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
