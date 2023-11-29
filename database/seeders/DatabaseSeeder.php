<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public const DURATION_IN_MONTHS  = 2; //7; // 25

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SalePointSeeder::class);
        $this->call(ReportSeeder::class);
        $this->call(WorkingRangeSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(AssetUnitSeeder::class);
        $this->call(AssetSeeder::class);
    }
}
