<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin
            'manage-users', 'manage-roles', 'manage-labs',
            'manage-locations', 'manage-farmers', 'manage-parameters',
            'manage-dropdowns', 'manage-custom-fields', 'manage-qr-codes',
            // Lab
            'view-lab-dashboard', 'manage-sample-queue', 'scan-qr',
            'perform-testing', 'view-reports',
            // Inventory
            'manage-inventory',
            // Targets
            'manage-targets',
            // Field
            'register-farmers', 'manage-plots', 'collect-samples',
            'track-samples', 'preprint-qr',
            // Notifications
            'manage-notifications',
            // Farmer
            'view-dashboard', 'download-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or update roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        $labManager = Role::firstOrCreate(['name' => 'Lab Manager']);
        $labManager->syncPermissions([
            'manage-users',
            'view-lab-dashboard', 'manage-sample-queue', 'scan-qr',
            'perform-testing', 'view-reports',
            'manage-inventory',
            'manage-targets',
            'register-farmers', 'manage-plots',
            'track-samples', 'preprint-qr',
        ]);

        $labTech = Role::firstOrCreate(['name' => 'Lab Technician']);
        $labTech->syncPermissions([
            'view-lab-dashboard', 'manage-sample-queue', 'scan-qr',
            'perform-testing', 'view-reports',
        ]);

        $fieldOfficial = Role::firstOrCreate(['name' => 'Field Official']);
        $fieldOfficial->syncPermissions([
            'register-farmers', 'manage-plots', 'collect-samples',
            'track-samples', 'preprint-qr',
        ]);

        $farmer = Role::firstOrCreate(['name' => 'Farmer']);
        $farmer->syncPermissions([
            'view-dashboard', 'download-reports',
        ]);
    }
}
