<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all permissions
        $allPermissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();

        // 1. Super Admin - Full Access
        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'admin']
        );
        $superAdmin->syncPermissions($allPermissions);
        $this->command->info('✅ Super Admin role created with all permissions');

        // 2. Manager - Full operational access, limited config
        $manager = Role::firstOrCreate(
            ['name' => 'Manager', 'guard_name' => 'admin']
        );
        $managerPermissions = [
            // Leads
            'lead-list', 'lead-view', 'lead-create', 'lead-edit', 'lead-delete', 'lead-assign', 'lead-status-change', 'lead-export',
            // Quotations
            'quotation-list', 'quotation-view', 'quotation-create', 'quotation-edit', 'quotation-delete', 'quotation-send', 'quotation-approve', 'quotation-convert', 'quotation-download',
            // Bookings
            'booking-list', 'booking-view', 'booking-create', 'booking-edit', 'booking-delete', 'booking-status-change', 'booking-assign-service', 'booking-export',
            // Payments
            'payment-list', 'payment-view', 'payment-create', 'payment-edit', 'payment-delete', 'payment-approve', 'payment-account-view',
            // Vendors
            'vendor-list', 'vendor-view', 'vendor-create', 'vendor-edit', 'vendor-delete', 'vendor-settlement-view', 'vendor-settlement-pay', 'vendor-assign',
            // Service Management
            'service-template-list', 'service-template-create', 'service-template-edit', 'service-template-delete',
            'service-type-list', 'service-type-create', 'service-type-edit', 'service-type-delete',
            'service-provider-list', 'service-provider-create', 'service-provider-edit', 'service-provider-delete',
            'service-item-list', 'service-item-create', 'service-item-edit', 'service-item-delete',
            // Analytics
            'analytics-customer', 'analytics-profit', 'analytics-export', 'report-generate', 'dashboard-view',
            // Activity Logs
            'activity-log-view', 'activity-log-export',
            // Staff (view only)
            'staff-list',
        ];
        $manager->syncPermissions($managerPermissions);
        $this->command->info('✅ Manager role created');

        // 3. Sales Executive - Leads & Quotations
        $salesExec = Role::firstOrCreate(
            ['name' => 'Sales Executive', 'guard_name' => 'admin']
        );
        $salesExecPermissions = [
            'lead-list', 'lead-view', 'lead-create', 'lead-edit', 'lead-assign',
            'quotation-list', 'quotation-view', 'quotation-create', 'quotation-edit', 'quotation-send',
            'booking-list', 'booking-view',
            'payment-list', 'payment-view',
            'vendor-list', 'vendor-view',
            'service-template-list', 'service-type-list', 'service-provider-list',
            'dashboard-view',
        ];
        $salesExec->syncPermissions($salesExecPermissions);
        $this->command->info('✅ Sales Executive role created');

        // 4. Operations Executive - Bookings & Services
        $opsExec = Role::firstOrCreate(
            ['name' => 'Operations Executive', 'guard_name' => 'admin']
        );
        $opsExecPermissions = [
            'lead-list', 'lead-view',
            'quotation-list', 'quotation-view',
            'booking-list', 'booking-view', 'booking-edit', 'booking-status-change', 'booking-assign-service',
            'payment-list', 'payment-view', 'payment-create',
            'vendor-list', 'vendor-view', 'vendor-assign',
            'service-template-list', 'service-template-create', 'service-template-edit', 'service-template-delete',
            'service-type-list', 'service-type-create', 'service-type-edit', 'service-type-delete',
            'service-provider-list', 'service-provider-create', 'service-provider-edit', 'service-provider-delete',
            'service-item-list', 'service-item-create', 'service-item-edit', 'service-item-delete',
            'dashboard-view',
        ];
        $opsExec->syncPermissions($opsExecPermissions);
        $this->command->info('✅ Operations Executive role created');

        // 5. Accountant - Financial Operations
        $accountant = Role::firstOrCreate(
            ['name' => 'Accountant', 'guard_name' => 'admin']
        );
        $accountantPermissions = [
            'lead-list', 'lead-view',
            'quotation-list', 'quotation-view',
            'booking-list', 'booking-view',
            'payment-list', 'payment-view', 'payment-create', 'payment-edit', 'payment-delete', 'payment-approve', 'payment-account-view', 'payment-account-manage',
            'vendor-settlement-view', 'vendor-settlement-pay',
            'analytics-profit',
            'activity-log-view',
            'dashboard-view',
        ];
        $accountant->syncPermissions($accountantPermissions);
        $this->command->info('✅ Accountant role created');

        // 6. Viewer - Read-Only Access
        $viewer = Role::firstOrCreate(
            ['name' => 'Viewer', 'guard_name' => 'admin']
        );
        $viewerPermissions = [
            'lead-list', 'lead-view',
            'quotation-list', 'quotation-view',
            'booking-list', 'booking-view',
            'payment-list', 'payment-view',
            'vendor-list', 'vendor-view',
            'dashboard-view',
        ];
        $viewer->syncPermissions($viewerPermissions);
        $this->command->info('✅ Viewer role created');

        $this->command->info('');
        $this->command->info('🎉 All role templates created successfully!');
        $this->command->info('');
        $this->command->info('Created roles:');
        $this->command->info('1. Super Admin - ' . count($allPermissions) . ' permissions');
        $this->command->info('2. Manager - ' . count($managerPermissions) . ' permissions');
        $this->command->info('3. Sales Executive - ' . count($salesExecPermissions) . ' permissions');
        $this->command->info('4. Operations Executive - ' . count($opsExecPermissions) . ' permissions');
        $this->command->info('5. Accountant - ' . count($accountantPermissions) . ' permissions');
        $this->command->info('6. Viewer - ' . count($viewerPermissions) . ' permissions');
    }
}
