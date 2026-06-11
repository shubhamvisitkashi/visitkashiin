<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedMissingServiceTypes extends Migration
{
    /**
     * Backfills service_types rows that exist on the dev database (created
     * via the admin Service Types screen) but are missing on other
     * environments. quotation_items.service_type_id has a RESTRICT foreign
     * key to this table, so a missing row (e.g. id 13 "Tour Package")
     * causes a 1452 error when saving a quotation.
     *
     * @return void
     */
    public function up()
    {
        $now = now();

        DB::table('service_types')->insertOrIgnore([
            ['id' => 4,  'name' => 'Guide',                   'slug' => 'guide',                  'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 5,  'name' => 'Photography',             'slug' => 'photography',            'is_active' => 1, 'terms_conditions' => '<p><br></p>', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6,  'name' => 'Decoration',              'slug' => 'Decoration',             'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 7,  'name' => 'Cake',                    'slug' => 'cake',                   'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 8,  'name' => 'Cattering (Local Food)',  'slug' => 'catteriing',             'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 9,  'name' => 'Cattering (Main Course)', 'slug' => 'cattering-main-course',  'is_active' => 0, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'name' => 'Cruise',                  'slug' => 'Cruise',                 'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'name' => 'Live Music Band',         'slug' => 'live-music-band',        'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'name' => 'Sound System',            'slug' => 'sound-system',           'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'name' => 'Tour Package',            'slug' => 'tour-package',           'is_active' => 1, 'terms_conditions' => null,        'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * @return void
     */
    public function down()
    {
        DB::table('service_types')->whereIn('id', [4, 5, 6, 7, 8, 9, 10, 11, 12, 13])->delete();
    }
}
