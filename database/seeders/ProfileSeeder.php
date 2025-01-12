<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;


class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        $profiles = [
            ['id' => (string) \Str::uuid(), 'name' => 'ADMIN', 'description' => 'Administrador del sistema', 'status' => true ],
            ['id' => (string) \Str::uuid(), 'name' => 'STUDENT', 'description' => 'Estudiante registrado', 'status' => true ],        
            ['id' => (string) \Str::uuid(), 'name' => 'TEACHER', 'description' => 'Docente registrado', 'status' => true ],        
        ];
        
        foreach ($profiles as $profile) {
            Profile::create($profile);
        }

    }
}
