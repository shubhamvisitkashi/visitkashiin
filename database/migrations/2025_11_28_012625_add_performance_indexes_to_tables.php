<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for performance optimization.
     * Adds indexes to frequently queried columns.
     */
    public function up(): void
    {
        // Leads table indexes
        Schema::table('leads', function (Blueprint $table) {
            $table->index('booking_status', 'idx_leads_booking_status');
            $table->index('lead_source_id', 'idx_leads_source_id');
            $table->index('added_by', 'idx_leads_added_by');
            $table->index(['booking_start_date', 'booking_end_date'], 'idx_leads_booking_dates');
            $table->index(['booking_status', 'added_by'], 'idx_leads_status_user');
        });

        // Quotations table indexes
        Schema::table('quotations', function (Blueprint $table) {
            $table->index('status', 'idx_quotations_status');
            $table->index('lead_id', 'idx_quotations_lead_id');
            $table->index('created_by', 'idx_quotations_created_by');
            $table->index('is_converted', 'idx_quotations_is_converted');
            $table->index(['status', 'is_converted'], 'idx_quotations_status_converted');
        });

        // Bookings table indexes
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('booking_status', 'idx_bookings_status');
            $table->index('lead_id', 'idx_bookings_lead_id');
            $table->index('quotation_id', 'idx_bookings_quotation_id');
            $table->index(['booking_status', 'lead_id'], 'idx_bookings_status_lead');
        });

        // Booking payments table indexes
        if (Schema::hasTable('booking_payments')) {
            Schema::table('booking_payments', function (Blueprint $table) {
                $table->index('booking_id', 'idx_booking_payments_booking_id');
                $table->index('payment_date', 'idx_booking_payments_date');
            });
        }

        // Lead payments table indexes
        if (Schema::hasTable('lead_payments')) {
            Schema::table('lead_payments', function (Blueprint $table) {
                $table->index('lead_id', 'idx_lead_payments_lead_id');
                $table->index('payment_date', 'idx_lead_payments_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('idx_leads_booking_status');
            $table->dropIndex('idx_leads_source_id');
            $table->dropIndex('idx_leads_added_by');
            $table->dropIndex('idx_leads_booking_dates');
            $table->dropIndex('idx_leads_status_user');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropIndex('idx_quotations_status');
            $table->dropIndex('idx_quotations_lead_id');
            $table->dropIndex('idx_quotations_created_by');
            $table->dropIndex('idx_quotations_is_converted');
            $table->dropIndex('idx_quotations_status_converted');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_status');
            $table->dropIndex('idx_bookings_lead_id');
            $table->dropIndex('idx_bookings_quotation_id');
            $table->dropIndex('idx_bookings_status_lead');
        });

        if (Schema::hasTable('booking_payments')) {
            Schema::table('booking_payments', function (Blueprint $table) {
                $table->dropIndex('idx_booking_payments_booking_id');
                $table->dropIndex('idx_booking_payments_date');
            });
        }

        if (Schema::hasTable('lead_payments')) {
            Schema::table('lead_payments', function (Blueprint $table) {
                $table->dropIndex('idx_lead_payments_lead_id');
                $table->dropIndex('idx_lead_payments_date');
            });
        }
    }
};
