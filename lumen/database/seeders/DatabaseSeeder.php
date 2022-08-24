<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Department,Employee,Occupation,User};

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();
        Department::factory()->count(10)->create();
        Occupation::factory()->count(30)->create();
        Employee::factory()->count(150)->create();
    }
}
