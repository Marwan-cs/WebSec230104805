<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create or ensure permissions exist
        $permissions = [
            'view_users',
            'edit_users',
            'delete_users',
            'create_users',
            'view_products',
            'edit_products',
            'delete_products',
            'create_products',
            'manage_roles',
            'view_reports',
            'export_data',
            'view_dashboard'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('yourpassword'),
                // 'admin' => true // Ensure this field exists in your users table
            ]);
        }
        
        // Assign admin role to the user
        $admin->assignRole('admin');
    }
}