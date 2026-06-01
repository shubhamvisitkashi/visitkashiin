<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingDetailsToEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('time_slot')->nullable()->after('arrival_date');
            $table->string('pickup_ghat')->nullable()->after('time_slot');
            $table->unsignedTinyInteger('children_count')->default(0)->after('no_of_person');
            $table->text('special_notes')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['time_slot', 'pickup_ghat', 'children_count', 'special_notes']);
        });
    }
}
