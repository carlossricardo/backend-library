<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorys = [
            ['id' => (string) \Str::uuid(), 'code' => 'CATE001', 'name' => 'Ciencia, conocimiento y organización', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE002', 'name' => 'Filosofía y psicología', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE003', 'name' => 'Religión y teología', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE004', 'name' => 'Ciencias sociales, economía, política, estadística y otras', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE005', 'name' => 'Matemáticas y ciencias naturales', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE006', 'name' => 'Ciencias aplicadas, medicina y tecnología', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE007', 'name' => 'Bellas artes, deportes y entretenimiento', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE008', 'name' => 'Lingüística, lenguaje y literatura', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE009', 'name' => 'Geografía, historia y biografías', 'description' => 'This is description', 'status' => true, ],          
            ['id' => (string) \Str::uuid(), 'code' => 'CATE0010', 'name' => 'Ciencias naturales', 'description' => 'This is description', 'status' => true, ],          
     
        ];
        
        foreach ($categorys as $category) {
            Category::create($category);
        }
    }

}
