<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // https://spatie.be/docs/laravel-permission/v5/basic-usage/teams-permissions#content-roles-creating
        $teamsRoles = ['manager', 'seller', 'admin', 'Super Admin'];


        foreach ($teamsRoles as $role) {
            Role::create([
                'name' => $role,
                //'team_id' => null
            ]);
        }

        $permissions = [
            'view_reports',
            'publish_report',
            'manage_reports',

            'view_stocks',
            'publish_report',
            'manage_stocks',

        ];
    }
}
