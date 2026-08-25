<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $perm = Permission::firstOrCreate(['name' => 'manage-targets']);

        Role::where('name', 'Admin')->first()?->givePermissionTo($perm);
        Role::where('name', 'Lab Manager')->first()?->givePermissionTo($perm);
    }

    public function down(): void
    {
        Permission::where('name', 'manage-targets')->delete();
    }
};
