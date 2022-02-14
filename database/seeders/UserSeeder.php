<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'      =>  'Adam',
                'email'     =>  'adam@email.com',
                'password'  =>  Hash::make('secret')
            ],
            [
                'name'      =>  'John',
                'email'     =>  'john@email.com',
                'password'  =>  Hash::make('secret')
            ],
            [
                'name'      =>  'Harry',
                'email'     =>  'harry@email.com',
                'password'  =>  Hash::make('secret')
            ],
        ];
        User::insert($users);
    }
}
