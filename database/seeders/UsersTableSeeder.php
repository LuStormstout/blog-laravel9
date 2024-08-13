<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(78)->create();

        $user = User::find(1);
        $user->name = 'LuStormstout';
        $user->email = 'lustormstout@gmail.com';
        $user->save();
    }
}
