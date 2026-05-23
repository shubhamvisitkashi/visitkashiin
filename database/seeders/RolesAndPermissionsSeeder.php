<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates all roles with their respective permissions
     * for the CRM system.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all permissions
        $allPermissions = Permission::pluck('id', 'id')->all();

        // ============================================
        // ROLE DEFINITIONS WITH ACCESS LEVELS
        // ============================================

        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'description' => 'Full system access - Can do everything',
                'permissions' => $allPermissions,
                'calendar_access' => 'all', // See all bookings
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'description' => 'Administrative access - Can manage most features',
                'permissions' => $allPermissions,
                'calendar_access' => 'all', // See all bookings
            ],
            [
                'id' => 3,
                'name' => 'Manager',
                'description' => 'Management access - Can view all data, limited editing',
                'permissions' => Permission::whereIn('name', [
                    'lead-list', 'lead-create', 'lead-edit',
                    'package-list',
                    'agent-list',
                    'enquiry-list',
                    'staff-list',
                ])->pluck('id', 'id')->all(),
                'calendar_access' => 'all', // See all bookings
            ],
            [
                'id' => 4,
                'name' => 'Staff',
                'description' => 'Staff member - Can only manage own leads and bookings',
                'permissions' => Permission::whereIn('name', [
                    'lead-list', 'lead-create', 'lead-edit',
                    'enquiry-list',
                ])->pluck('id', 'id')->all(),
                'calendar_access' => 'own', // See only own bookings
            ],
            [
                'id' => 5,
                'name' => 'Sales',
                'description' => 'Sales team - Focus on leads and packages',
                'permissions' => Permission::whereIn('name', [
                    'lead-list', 'lead-create', 'lead-edit',
                    'package-list',
                    'enquiry-list',
                ])->pluck('id', 'id')->all(),
                'calendar_access' => 'own', // See only own bookings
            ],
            [
                'id' => 6,
                'name' => 'Accountant',
                'description' => 'Financial access - Can view bookings and manage payments',
                'permissions' => Permission::whereIn('name', [
                    'lead-list',
                    'package-list',
                ])->pluck('id', 'id')->all(),
                'calendar_access' => 'all', // See all bookings for financial tracking
            ],
            [
                'id' => 7,
                'name' => 'Viewer',
                'description' => 'Read-only access - Can only view data',
                'permissions' => Permission::whereIn('name', [
                    'lead-list',
                    'package-list',
                    'agent-list',
                    'enquiry-list',
                    'staff-list',
                ])->pluck('id', 'id')->all(),
                'calendar_access' => 'all', // See all bookings (read-only)
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['id' => $roleData['id']],
                [
                    'guard_name' => 'admin',
                    'name' => $roleData['name']
                ]
            );

            // Sync permissions
            $role->syncPermissions($roleData['permissions']);

            $this->command->info("✅ {$roleData['name']} - {$roleData['description']}");
            $this->command->info("   📅 Calendar Access: {$roleData['calendar_access']}");
            $this->command->info("   🔑 Permissions: " . count($roleData['permissions']));
        }

        $this->command->info("\n🎉 All roles and permissions seeded successfully!");
    }
}
