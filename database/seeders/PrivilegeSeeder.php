<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Privilege;
use App\Models\Profile;



class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $privileges = [
            ['id' => (string) \Str::uuid(), 'name' => 'READ_PRIVILEGE', 'description' => 'READ_PRIVILEGE description', 'status' => true, 'profiles' => ['ADMIN', 'STUDENT', 'TEACHER'] ],
            ['id' => (string) \Str::uuid(), 'name' => 'WRITE_PRIVILEGE', 'description' => 'WRITE_PRIVILEGE description', 'status' => true, 'profiles' => ['ADMIN', 'STUDENT', 'TEACHER'] ],        
            ['id' => (string) \Str::uuid(), 'name' => 'UPDATE_PRIVILEGE', 'description' => 'UPDATE_PRIVILEGE description', 'status' => true, 'profiles' => ['ADMIN'] ],        
            ['id' => (string) \Str::uuid(), 'name' => 'DELETE_PRIVILEGE', 'description' => 'DELETE_PRIVILEGE description', 'status' => true, 'profiles' => ['ADMIN'] ],        
        ];
        
        foreach ($privileges as $privilege) {
            

            $newPrivilege = Privilege::create([
                'id' => $privilege['id'],
                'name' => $privilege['name'],
                'description' => $privilege['description'],
                'status' => $privilege['status']
            ]);


            
            $profileIds = Profile::whereIn('name', $privilege['profiles'])->pluck('id');

            
            $newPrivilege->profiles()->attach($profileIds);
        }
    }
}
