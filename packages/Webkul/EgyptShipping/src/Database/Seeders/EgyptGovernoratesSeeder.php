<?php

namespace Webkul\EgyptShipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EgyptGovernoratesSeeder extends Seeder
{
    public function run(): void
    {
        $governorates = [
            ['code' => 'cairo',        'name_ar' => 'القاهرة',       'name_en' => 'Cairo'],
            ['code' => 'alexandria',   'name_ar' => 'الإسكندرية',    'name_en' => 'Alexandria'],
            ['code' => 'giza',         'name_ar' => 'الجيزة',        'name_en' => 'Giza'],
            ['code' => 'qalyubia',     'name_ar' => 'القليوبية',     'name_en' => 'Qalyubia'],
            ['code' => 'port_said',    'name_ar' => 'بورسعيد',       'name_en' => 'Port Said'],
            ['code' => 'suez',         'name_ar' => 'السويس',        'name_en' => 'Suez'],
            ['code' => 'luxor',        'name_ar' => 'الأقصر',        'name_en' => 'Luxor'],
            ['code' => 'aswan',        'name_ar' => 'أسوان',         'name_en' => 'Aswan'],
            ['code' => 'asyut',        'name_ar' => 'أسيوط',         'name_en' => 'Asyut'],
            ['code' => 'beheira',      'name_ar' => 'البحيرة',       'name_en' => 'Beheira'],
            ['code' => 'beni_suef',    'name_ar' => 'بني سويف',      'name_en' => 'Beni Suef'],
            ['code' => 'dakahlia',     'name_ar' => 'الدقهلية',      'name_en' => 'Dakahlia'],
            ['code' => 'damietta',     'name_ar' => 'دمياط',         'name_en' => 'Damietta'],
            ['code' => 'faiyum',       'name_ar' => 'الفيوم',        'name_en' => 'Faiyum'],
            ['code' => 'gharbia',      'name_ar' => 'الغربية',       'name_en' => 'Gharbia'],
            ['code' => 'ismailia',     'name_ar' => 'الإسماعيلية',   'name_en' => 'Ismailia'],
            ['code' => 'kafr_el_sheikh','name_ar' => 'كفر الشيخ',    'name_en' => 'Kafr El Sheikh'],
            ['code' => 'matruh',       'name_ar' => 'مطروح',         'name_en' => 'Matruh'],
            ['code' => 'minya',        'name_ar' => 'المنيا',        'name_en' => 'Minya'],
            ['code' => 'monufia',      'name_ar' => 'المنوفية',      'name_en' => 'Monufia'],
            ['code' => 'new_valley',   'name_ar' => 'الوادي الجديد', 'name_en' => 'New Valley'],
            ['code' => 'north_sinai',  'name_ar' => 'شمال سيناء',    'name_en' => 'North Sinai'],
            ['code' => 'red_sea',      'name_ar' => 'البحر الأحمر',  'name_en' => 'Red Sea'],
            ['code' => 'sharqia',      'name_ar' => 'الشرقية',       'name_en' => 'Sharqia'],
            ['code' => 'sohag',        'name_ar' => 'سوهاج',         'name_en' => 'Sohag'],
            ['code' => 'south_sinai',  'name_ar' => 'جنوب سيناء',    'name_en' => 'South Sinai'],
            ['code' => 'qena',         'name_ar' => 'قنا',           'name_en' => 'Qena'],
        ];

        $now = now();

        foreach ($governorates as $gov) {
            $exists = DB::table('egypt_shipping_governorates')->where('code', $gov['code'])->exists();

            if ($exists) {
                // Only update names — never overwrite rates set via admin
                DB::table('egypt_shipping_governorates')->where('code', $gov['code'])->update([
                    'name_ar'    => $gov['name_ar'],
                    'name_en'    => $gov['name_en'],
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('egypt_shipping_governorates')->insert(
                    array_merge($gov, ['is_active' => true, 'rate' => null, 'created_at' => $now, 'updated_at' => $now])
                );
            }
        }
    }
}
