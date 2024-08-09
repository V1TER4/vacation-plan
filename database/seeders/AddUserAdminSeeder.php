<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class AddUserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'  => 'System',
            'email' => 'system@system.com.br',
            'password' => Hash::make('jklqwe9721@')
        ]);
    }
}
