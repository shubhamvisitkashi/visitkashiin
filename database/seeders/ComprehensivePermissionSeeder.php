<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin\Admin;

class ComprehensivePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            // ── Dashboard ──────────────────────────────────────────
            ['name' => 'dashboard-view',            'parent' => 'dashboard'],

            // ── Lead Module ────────────────────────────────────────
            ['name' => 'lead-list',                 'parent' => 'lead'],
            ['name' => 'lead-view',                 'parent' => 'lead'],
            ['name' => 'lead-create',               'parent' => 'lead'],
            ['name' => 'lead-edit',                 'parent' => 'lead'],
            ['name' => 'lead-delete',               'parent' => 'lead'],
            ['name' => 'lead-assign',               'parent' => 'lead'],
            ['name' => 'lead-status-change',        'parent' => 'lead'],
            ['name' => 'lead-export',               'parent' => 'lead'],

            // ── Quotation Module ───────────────────────────────────
            ['name' => 'quotation-list',            'parent' => 'quotation'],
            ['name' => 'quotation-view',            'parent' => 'quotation'],
            ['name' => 'quotation-create',          'parent' => 'quotation'],
            ['name' => 'quotation-edit',            'parent' => 'quotation'],
            ['name' => 'quotation-delete',          'parent' => 'quotation'],
            ['name' => 'quotation-send',            'parent' => 'quotation'],
            ['name' => 'quotation-approve',         'parent' => 'quotation'],
            ['name' => 'quotation-convert',         'parent' => 'quotation'],
            ['name' => 'quotation-download',        'parent' => 'quotation'],

            // ── Booking Module ─────────────────────────────────────
            ['name' => 'booking-list',              'parent' => 'booking'],
            ['name' => 'booking-view',              'parent' => 'booking'],
            ['name' => 'booking-create',            'parent' => 'booking'],
            ['name' => 'booking-edit',              'parent' => 'booking'],
            ['name' => 'booking-delete',            'parent' => 'booking'],
            ['name' => 'booking-status-change',     'parent' => 'booking'],
            ['name' => 'booking-assign-service',    'parent' => 'booking'],
            ['name' => 'booking-export',            'parent' => 'booking'],

            // ── Payment Module ─────────────────────────────────────
            ['name' => 'payment-list',              'parent' => 'payment'],
            ['name' => 'payment-view',              'parent' => 'payment'],
            ['name' => 'payment-create',            'parent' => 'payment'],
            ['name' => 'payment-edit',              'parent' => 'payment'],
            ['name' => 'payment-delete',            'parent' => 'payment'],
            ['name' => 'payment-approve',           'parent' => 'payment'],
            ['name' => 'payment-account-view',      'parent' => 'payment'],
            ['name' => 'payment-account-manage',    'parent' => 'payment'],

            // ── Category ───────────────────────────────────────────
            ['name' => 'category-list',             'parent' => 'category'],
            ['name' => 'category-create',           'parent' => 'category'],
            ['name' => 'category-edit',             'parent' => 'category'],
            ['name' => 'category-delete',           'parent' => 'category'],

            // ── Sub Category ───────────────────────────────────────
            ['name' => 'sub_category-list',         'parent' => 'sub_category'],
            ['name' => 'sub_category-create',       'parent' => 'sub_category'],
            ['name' => 'sub_category-edit',         'parent' => 'sub_category'],
            ['name' => 'sub_category-delete',       'parent' => 'sub_category'],

            // ── Package / Products ─────────────────────────────────
            ['name' => 'package-list',              'parent' => 'package'],
            ['name' => 'package-create',            'parent' => 'package'],
            ['name' => 'package-edit',              'parent' => 'package'],
            ['name' => 'package-delete',            'parent' => 'package'],

            // ── Enquiry ────────────────────────────────────────────
            ['name' => 'enquiry-list',              'parent' => 'enquiry'],
            ['name' => 'enquiry-delete',            'parent' => 'enquiry'],

            // ── Agent ──────────────────────────────────────────────
            ['name' => 'agent-list',                'parent' => 'agent'],
            ['name' => 'agent-create',              'parent' => 'agent'],
            ['name' => 'agent-edit',                'parent' => 'agent'],
            ['name' => 'agent-delete',              'parent' => 'agent'],

            // ── Staff Management ───────────────────────────────────
            ['name' => 'staff-list',                'parent' => 'staff'],
            ['name' => 'staff-create',              'parent' => 'staff'],
            ['name' => 'staff-edit',                'parent' => 'staff'],
            ['name' => 'staff-delete',              'parent' => 'staff'],

            // ── Roles & Permissions ────────────────────────────────
            ['name' => 'role-list',                 'parent' => 'role'],
            ['name' => 'role-create',               'parent' => 'role'],
            ['name' => 'role-edit',                 'parent' => 'role'],
            ['name' => 'role-delete',               'parent' => 'role'],

            // ── Vendor ────────────────────────────────────────────
            ['name' => 'vendor-list',               'parent' => 'vendor'],
            ['name' => 'vendor-create',             'parent' => 'vendor'],
            ['name' => 'vendor-edit',               'parent' => 'vendor'],
            ['name' => 'vendor-delete',             'parent' => 'vendor'],
            ['name' => 'vendor-settlement-view',    'parent' => 'vendor'],
            ['name' => 'vendor-settlement-pay',     'parent' => 'vendor'],
            ['name' => 'vendor-assign',             'parent' => 'vendor'],

            // ── Service Templates ──────────────────────────────────
            ['name' => 'service-template-list',     'parent' => 'service-template'],
            ['name' => 'service-template-create',   'parent' => 'service-template'],
            ['name' => 'service-template-edit',     'parent' => 'service-template'],
            ['name' => 'service-template-delete',   'parent' => 'service-template'],

            // ── Service Types ──────────────────────────────────────
            ['name' => 'service-type-list',         'parent' => 'service-type'],
            ['name' => 'service-type-create',       'parent' => 'service-type'],
            ['name' => 'service-type-edit',         'parent' => 'service-type'],
            ['name' => 'service-type-delete',       'parent' => 'service-type'],

            // ── Analytics & Reports ────────────────────────────────
            ['name' => 'analytics-customer',        'parent' => 'analytics'],
            ['name' => 'analytics-profit',          'parent' => 'analytics'],
            ['name' => 'analytics-export',          'parent' => 'analytics'],
            ['name' => 'report-generate',           'parent' => 'analytics'],

            // ── Activity Logs ──────────────────────────────────────
            ['name' => 'activity-log-view',         'parent' => 'activity-log'],
            ['name' => 'activity-log-export',       'parent' => 'activity-log'],
            ['name' => 'activity-log-delete',       'parent' => 'activity-log'],

            // ── Website Setup ──────────────────────────────────────
            ['name' => 'web_setup',                 'parent' => 'web_setup'],
            ['name' => 'web-setup',                 'parent' => 'web_setup'],
        ];

        $created = 0;
        foreach ($permissions as $perm) {
            $existing = Permission::where('name', $perm['name'])->where('guard_name', 'admin')->first();
            if (!$existing) {
                Permission::create([
                    'name'        => $perm['name'],
                    'parent_name' => $perm['parent'],
                    'guard_name'  => 'admin',
                ]);
                $created++;
            } else {
                // Update parent_name in case it changed
                $existing->update(['parent_name' => $perm['parent']]);
            }
        }

        // Give ALL permissions to Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        if ($superAdminRole) {
            $allPermissions = Permission::where('guard_name', 'admin')->pluck('id', 'id')->all();
            $superAdminRole->syncPermissions($allPermissions);
        }

        // Give ALL permissions to Admin role too
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::where('guard_name', 'admin')->pluck('id', 'id')->all();
            $adminRole->syncPermissions($allPermissions);
        }

        // Also directly assign all permissions to admin user id=1
        $superAdmin = \App\Models\Admin\Admin::find(1);
        if ($superAdmin) {
            $allPermissions = Permission::where('guard_name', 'admin')->pluck('id', 'id')->all();
            $superAdmin->syncPermissions($allPermissions);
        }

        $this->command->info("✅ Permissions synced — {$created} new created, all existing updated.");
        $this->command->info('✅ Super Admin & Admin roles granted ALL permissions.');
        $this->command->info('✅ Admin user (id=1) granted ALL permissions directly.');
    }
}
