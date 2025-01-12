<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Person;
use App\Models\Profile;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [


            [
                'email' => 'carlos.sricardo@hotmail.com', 
                'password' => bcrypt('0622Reyes'), 
                'status' => true,
                'profiles' => ['STUDENT'],
                'person' => [
                    'identification' => '2450661760',
                    'names' => 'Carlos',
                    'surnames' => 'Ricardo',
                    'image' => 'fd7f7587-d385-4e64-b0a0-99d2d89a8ec2_20250106_134020_default.jpg',
                    'phone' => '0986757532',
                    'status' => true,
                ]
            ],
            [
                'email' => 'sadmin@hotmail.com', 
                'password' => bcrypt('Admin123'), 
                'status' => true,
                'profiles' => ['ADMIN', 'STUDENT'],
                'person' => [
                    'identification' => '2485749863',
                    'names' => 'Nombre',
                    'surnames' => 'Administrador',
                    'image' => 'fd7f7587-d385-4e64-b0a0-99d2d89a8ec2_20250106_134020_default.jpg',
                    'phone' => '0986757532',
                    'status' => true,
                ]
            ],

        ];
        
     

        foreach ($users as $userData) {
            
            $person = Person::create([
                'id' => (string) \Str::uuid(),
                'identification' => $userData['person']['identification'],
                'names' => $userData['person']['names'],
                'surnames' => $userData['person']['surnames'],
                'image' => $userData['person']['image'],
                'phone' => $userData['person']['phone'],
                'status' => $userData['person']['status'],
            ]);

            
            $user = User::create([
                'id' => (string) \Str::uuid(),
                'person_id' => $person->id, 
                'email' => $userData['email'],
                'password' => $userData['password'],
                'status' => $userData['status'],
            ]);

            
            $profileIds = Profile::whereIn('name', $userData['profiles'])->pluck('id');
            $user->profiles()->attach($profileIds);
        }
    }
}

