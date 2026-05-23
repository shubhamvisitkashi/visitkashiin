<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin User
        $super_admin = Admin::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789')
        ]);

        // Get all permissions
        $allPermissions = Permission::pluck('id', 'id')->all();

        // ============================================
        // 1. SUPER ADMIN ROLE - Full Access
        // ============================================
        $superAdminRole = Role::updateOrCreate([
            'id' => 1
        ], [
            'guard_name' => 'admin',
            'name' => 'Super Admin'
        ]);
        $superAdminRole->syncPermissions($allPermissions);
        $super_admin->assignRole([$superAdminRole->id]);

        // ============================================
        // 2. ADMIN ROLE - Full Access (same as Super Admin)
        // ============================================
        $adminRole = Role::updateOrCreate([
            'id' => 2
        ], [
            'guard_name' => 'admin',
            'name' => 'Admin'
        ]);
        $adminRole->syncPermissions($allPermissions);

        // ============================================
        // 3. MANAGER ROLE - Can view all, limited edit
        // ============================================
        $managerPermissions = Permission::whereIn('name', [
            'lead-list',
            'lead-create',
            'lead-edit',
            'package-list',
            'agent-list',
            'enquiry-list',
            'staff-list',
            // Add booking-related permissions if they exist
        ])->pluck('id', 'id')->all();

        $managerRole = Role::updateOrCreate([
            'id' => 3
        ], [
            'guard_name' => 'admin',
            'name' => 'Manager'
        ]);
        $managerRole->syncPermissions($managerPermissions);

        // ============================================
        // 4. STAFF ROLE - Limited access, own data only
        // ============================================
        $staffPermissions = Permission::whereIn('name', [
            'lead-list',
            'lead-create',
            'lead-edit',
            'enquiry-list',
            // Staff can manage their own leads and bookings
        ])->pluck('id', 'id')->all();

        $staffRole = Role::updateOrCreate([
            'id' => 4
        ], [
            'guard_name' => 'admin',
            'name' => 'Staff'
        ]);
        $staffRole->syncPermissions($staffPermissions);

        // ============================================
        // 5. SALES ROLE - Sales-focused permissions
        // ============================================
        $salesPermissions = Permission::whereIn('name', [
            'lead-list',
            'lead-create',
            'lead-edit',
            'package-list',
            'enquiry-list',
        ])->pluck('id', 'id')->all();

        $salesRole = Role::updateOrCreate([
            'id' => 5
        ], [
            'guard_name' => 'admin',
            'name' => 'Sales'
        ]);
        $salesRole->syncPermissions($salesPermissions);

        // ============================================
        // 6. ACCOUNTANT ROLE - Financial access
        // ============================================
        $accountantPermissions = Permission::whereIn('name', [
            'lead-list',
            'package-list',
            // Add payment/invoice permissions when available
        ])->pluck('id', 'id')->all();

        $accountantRole = Role::updateOrCreate([
            'id' => 6
        ], [
            'guard_name' => 'admin',
            'name' => 'Accountant'
        ]);
        $accountantRole->syncPermissions($accountantPermissions);

        // ============================================
        // 7. VIEWER ROLE - Read-only access
        // ============================================
        $viewerPermissions = Permission::whereIn('name', [
            'lead-list',
            'package-list',
            'agent-list',
            'enquiry-list',
            'staff-list',
        ])->pluck('id', 'id')->all();

        $viewerRole = Role::updateOrCreate([
            'id' => 7
        ], [
            'guard_name' => 'admin',
            'name' => 'Viewer'
        ]);
        $viewerRole->syncPermissions($viewerPermissions);

        $this->command->info('✅ All roles created successfully!');
        $this->command->info('📋 Roles created:');
        $this->command->info('   1. Super Admin - Full access to everything');
        $this->command->info('   2. Admin - Full access to everything');
        $this->command->info('   3. Manager - Can view all bookings, limited edit');
        $this->command->info('   4. Staff - Can only see own bookings');
        $this->command->info('   5. Sales - Sales-focused permissions');
        $this->command->info('   6. Accountant - Financial access');
        $this->command->info('   7. Viewer - Read-only access');
    }
}
