<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeQuotationIdNullableInBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to modify the column
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_quotation_id_foreign');
        DB::statement('ALTER TABLE bookings MODIFY quotation_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse the changes
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_quotation_id_foreign');
        DB::statement('ALTER TABLE bookings MODIFY quotation_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations(id)');
    }
}
