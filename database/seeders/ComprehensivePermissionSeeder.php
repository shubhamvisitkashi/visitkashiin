<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ComprehensivePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Lead Module
            ['name' => 'lead-list', 'parent' => 'lead', 'description' => 'View list of leads'],
            ['name' => 'lead-view', 'parent' => 'lead', 'description' => 'View individual lead details'],
            ['name' => 'lead-create', 'parent' => 'lead', 'description' => 'Create new leads'],
            ['name' => 'lead-edit', 'parent' => 'lead', 'description' => 'Edit lead information'],
            ['name' => 'lead-delete', 'parent' => 'lead', 'description' => 'Delete leads'],
            ['name' => 'lead-assign', 'parent' => 'lead', 'description' => 'Assign leads to staff'],
            ['name' => 'lead-status-change', 'parent' => 'lead', 'description' => 'Change lead status'],
            ['name' => 'lead-export', 'parent' => 'lead', 'description' => 'Export lead data'],

            // Quotation Module
            ['name' => 'quotation-list', 'parent' => 'quotation', 'description' => 'View list of quotations'],
            ['name' => 'quotation-view', 'parent' => 'quotation', 'description' => 'View quotation details'],
            ['name' => 'quotation-create', 'parent' => 'quotation', 'description' => 'Create new quotations'],
            ['name' => 'quotation-edit', 'parent' => 'quotation', 'description' => 'Edit quotations'],
            ['name' => 'quotation-delete', 'parent' => 'quotation', 'description' => 'Delete quotations'],
            ['name' => 'quotation-send', 'parent' => 'quotation', 'description' => 'Send quotations to customers'],
            ['name' => 'quotation-approve', 'parent' => 'quotation', 'description' => 'Approve quotations'],
            ['name' => 'quotation-convert', 'parent' => 'quotation', 'description' => 'Convert quotations to bookings'],
            ['name' => 'quotation-download', 'parent' => 'quotation', 'description' => 'Download quotation PDFs'],

            // Booking Module
            ['name' => 'booking-list', 'parent' => 'booking', 'description' => 'View list of bookings'],
            ['name' => 'booking-view', 'parent' => 'booking', 'description' => 'View booking details'],
            ['name' => 'booking-create', 'parent' => 'booking', 'description' => 'Create new bookings'],
            ['name' => 'booking-edit', 'parent' => 'booking', 'description' => 'Edit booking information'],
            ['name' => 'booking-delete', 'parent' => 'booking', 'description' => 'Delete bookings'],
            ['name' => 'booking-status-change', 'parent' => 'booking', 'description' => 'Change booking status'],
            ['name' => 'booking-assign-service', 'parent' => 'booking', 'description' => 'Assign services to vendors'],
            ['name' => 'booking-export', 'parent' => 'booking', 'description' => 'Export booking data'],

            // Payment Module
            ['name' => 'payment-list', 'parent' => 'payment', 'description' => 'View payment list'],
            ['name' => 'payment-view', 'parent' => 'payment', 'description' => 'View payment details'],
            ['name' => 'payment-create', 'parent' => 'payment', 'description' => 'Record new payments'],
            ['name' => 'payment-edit', 'parent' => 'payment', 'description' => 'Edit payment records'],
            ['name' => 'payment-delete', 'parent' => 'payment', 'description' => 'Delete payment records'],
            ['name' => 'payment-approve', 'parent' => 'payment', 'description' => 'Approve payments'],
            ['name' => 'payment-account-view', 'parent' => 'payment', 'description' => 'View payment accounts'],
            ['name' => 'payment-account-manage', 'parent' => 'payment', 'description' => 'Manage payment accounts'],

            // Vendor Management
            ['name' => 'vendor-list', 'parent' => 'vendor', 'description' => 'View vendor list'],
            ['name' => 'vendor-view', 'parent' => 'vendor', 'description' => 'View vendor details'],
            ['name' => 'vendor-create', 'parent' => 'vendor', 'description' => 'Add new vendors'],
            ['name' => 'vendor-edit', 'parent' => 'vendor', 'description' => 'Edit vendor information'],
            ['name' => 'vendor-delete', 'parent' => 'vendor', 'description' => 'Delete vendors'],
            ['name' => 'vendor-settlement-view', 'parent' => 'vendor', 'description' => 'View vendor settlements'],
            ['name' => 'vendor-settlement-pay', 'parent' => 'vendor', 'description' => 'Make vendor payments'],
            ['name' => 'vendor-assign', 'parent' => 'vendor', 'description' => 'Assign services to vendors'],

            // Service Templates
            ['name' => 'service-template-list', 'parent' => 'service-template', 'description' => 'View service templates'],
            ['name' => 'service-template-create', 'parent' => 'service-template', 'description' => 'Create service templates'],
            ['name' => 'service-template-edit', 'parent' => 'service-template', 'description' => 'Edit service templates'],
            ['name' => 'service-template-delete', 'parent' => 'service-template', 'description' => 'Delete service templates'],

            // Service Types
            ['name' => 'service-type-list', 'parent' => 'service-type', 'description' => 'View service types'],
            ['name' => 'service-type-create', 'parent' => 'service-type', 'description' => 'Create service types'],
            ['name' => 'service-type-edit', 'parent' => 'service-type', 'description' => 'Edit service types'],
            ['name' => 'service-type-delete', 'parent' => 'service-type', 'description' => 'Delete service types'],

            // Service Providers
            ['name' => 'service-provider-list', 'parent' => 'service-provider', 'description' => 'View service providers'],
            ['name' => 'service-provider-create', 'parent' => 'service-provider', 'description' => 'Create service providers'],
            ['name' => 'service-provider-edit', 'parent' => 'service-provider', 'description' => 'Edit service providers'],
            ['name' => 'service-provider-delete', 'parent' => 'service-provider', 'description' => 'Delete service providers'],

            // Service Items
            ['name' => 'service-item-list', 'parent' => 'service-item', 'description' => 'View service items'],
            ['name' => 'service-item-create', 'parent' => 'service-item', 'description' => 'Create service items'],
            ['name' => 'service-item-edit', 'parent' => 'service-item', 'description' => 'Edit service items'],
            ['name' => 'service-item-delete', 'parent' => 'service-item', 'description' => 'Delete service items'],

            // Analytics & Reports
            ['name' => 'analytics-customer', 'parent' => 'analytics', 'description' => 'View customer analytics'],
            ['name' => 'analytics-profit', 'parent' => 'analytics', 'description' => 'View profit analytics'],
            ['name' => 'analytics-export', 'parent' => 'analytics', 'description' => 'Export analytics data'],
            ['name' => 'report-generate', 'parent' => 'analytics', 'description' => 'Generate custom reports'],
            ['name' => 'dashboard-view', 'parent' => 'analytics', 'description' => 'View dashboard'],

            // Activity Logs
            ['name' => 'activity-log-view', 'parent' => 'activity-log', 'description' => 'View activity logs'],
            ['name' => 'activity-log-export', 'parent' => 'activity-log', 'description' => 'Export activity logs'],
            ['name' => 'activity-log-delete', 'parent' => 'activity-log', 'description' => 'Delete old activity logs'],

            // Existing System Permissions (Keep for compatibility)
            ['name' => 'category-list', 'parent' => 'category', 'description' => 'View category list'],
            ['name' => 'category-create', 'parent' => 'category', 'description' => 'Create categories'],
            ['name' => 'category-edit', 'parent' => 'category', 'description' => 'Edit categories'],
            ['name' => 'category-delete', 'parent' => 'category', 'description' => 'Delete categories'],

            ['name' => 'sub_category-list', 'parent' => 'sub_category', 'description' => 'View sub-category list'],
            ['name' => 'sub_category-create', 'parent' => 'sub_category', 'description' => 'Create sub-categories'],
            ['name' => 'sub_category-edit', 'parent' => 'sub_category', 'description' => 'Edit sub-categories'],
            ['name' => 'sub_category-delete', 'parent' => 'sub_category', 'description' => 'Delete sub-categories'],

            ['name' => 'package-list', 'parent' => 'package', 'description' => 'View package list'],
            ['name' => 'package-create', 'parent' => 'package', 'description' => 'Create packages'],
            ['name' => 'package-edit', 'parent' => 'package', 'description' => 'Edit packages'],
            ['name' => 'package-delete', 'parent' => 'package', 'description' => 'Delete packages'],

            ['name' => 'agent-list', 'parent' => 'agent', 'description' => 'View agent list'],
            ['name' => 'agent-create', 'parent' => 'agent', 'description' => 'Create agents'],
            ['name' => 'agent-edit', 'parent' => 'agent', 'description' => 'Edit agents'],
            ['name' => 'agent-delete', 'parent' => 'agent', 'description' => 'Delete agents'],

            ['name' => 'enquiry-list', 'parent' => 'enquiry', 'description' => 'View enquiry list'],
            ['name' => 'enquiry-delete', 'parent' => 'enquiry', 'description' => 'Delete enquiries'],

            ['name' => 'staff-list', 'parent' => 'staff', 'description' => 'View staff list'],
            ['name' => 'staff-create', 'parent' => 'staff', 'description' => 'Create staff'],
            ['name' => 'staff-edit', 'parent' => 'staff', 'description' => 'Edit staff'],
            ['name' => 'staff-delete', 'parent' => 'staff', 'description' => 'Delete staff'],

            ['name' => 'role-list', 'parent' => 'role', 'description' => 'View role list'],
            ['name' => 'role-create', 'parent' => 'role', 'description' => 'Create roles'],
            ['name' => 'role-edit', 'parent' => 'role', 'description' => 'Edit roles'],
            ['name' => 'role-delete', 'parent' => 'role', 'description' => 'Delete roles'],

            ['name' => 'web-setup', 'parent' => 'web-setup', 'description' => 'Manage website setup'],
        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::where('name', $permissionData['name'])->first();
            
            if (!$permission) {
                $permission = new Permission();
            }
            
            $permission->name = $permissionData['name'];
            $permission->parent_name = $permissionData['parent'];
            $permission->guard_name = 'admin';
            $permission->save();
        }

        $this->command->info('✅ Created ' . count($permissions) . ' permissions successfully!');
    }
}
