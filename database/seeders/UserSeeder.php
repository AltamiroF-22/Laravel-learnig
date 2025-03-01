<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = [
            [
                'name' => 'AltamiroF-22',
                'email' => 'altamiroribeirodarocha@gmail.com',
            ],
            [
                'name' => 'JuniorF-22',
                'email' => 'junior@gmail.com',
            ],
            [
                'name' => 'TableF-22',
                'email' => 'thebookisonthetable@gmail.com',
            ]
        ];

        for ($i = 0; $i < 47; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
            ];
        }

        foreach ($users as $user) {
            if (!User::where('email', $user['email'])->exists()) {
                User::create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make('1234567890', ['rounds' => 12]),
                ]);
            }
        }
    }
}
