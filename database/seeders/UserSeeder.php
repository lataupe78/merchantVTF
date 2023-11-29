<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users  = [
            'Riri' => ['email' => 'riri@example.com', 'role' => 'manager'],
            'Loulou' => ['email' => 'loulou@example.com', 'role' => 'manager'],
            'Jean-Charles NomSuperLongPourVoir' => ['email' => 'jean-charles.nomsuperlongpourvoir@example.com',],
            'Fifi' => ['email' => 'fifi@example.com',],
            'nini' => ['email' => 'nini@example.com'],
            'bobo' => ['email' => 'bobo@example.com'],
            'bebert' => ['email' => 'bebert@example.com'],
            'momo' => ['email' => 'momo@example.com'],
            // 'gege' => ['email' => 'gege@example.com',],
            // 'lulu' => ['email' => 'lulu@example.com'],
            // 'gigi' => ['email' => 'gigi@example.com',],
        ];

        $password = Hash::make('secret');

        // we uses createQuietly to avoid send verify email notification ( User implements MustVerifyEmail)

        $Pascal = User::factory()
            ->createQuietly([
                'name' => 'Pascal',
                'email' => 'force.rouge.1978@gmail.com',
                'phone' => '0652784646',
                'password' => $password,
            ]);

        $Pascal->assignRole('Super Admin');

        foreach ($users as $name => $user) {

            $newUser = User::where('email', $user['email'])
                ->first();

            if ($newUser == null) {
                $newUser = User::factory()
                    ->createQuietly([
                        'name' => $name,
                        'password' => $password,
                    ]);
            }

            $role = $user['role'] ?? 'seller';
            $newUser->assignRole($role);
        }
    }
}
