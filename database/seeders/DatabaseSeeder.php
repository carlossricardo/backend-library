<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call(ProfileSeeder::class);
        $this->call(PrivilegeSeeder::class);

        $this->call(OptionSeeder::class);
        $this->call(UserSeeder::class);
        
        $this->call(CategorySeeder::class);
        $this->call(BookSeeder::class);

    }


    // php artisan db:seed
    // php artisan migrate --seed
    // php artisan migrate:fresh --seed
}
