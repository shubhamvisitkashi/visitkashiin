<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddGstFieldsToWebsiteSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert GST-related settings into website_setups table
        DB::table('website_setups')->insert([
            [
                'name' => 'company_gstin',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'company_legal_name',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('website_setups')
            ->whereIn('name', ['company_gstin', 'company_legal_name'])
            ->delete();
    }
}
