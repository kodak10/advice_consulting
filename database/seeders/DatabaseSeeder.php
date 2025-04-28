<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PaysSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            BanqueSeeder::class,
            DesignationSeeder::class,
            DeviseSeeder::class,
        ]);

       
    }
}
