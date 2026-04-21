<?php

namespace Database\Seeders;

use App\Models\SiteStatistic;
use Illuminate\Database\Seeder;

class SiteStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['label_lo' => 'ປະເທດຄູ່ຮ່ວມ',  'label_en' => 'Partner Countries',  'label_zh' => '合作国家',  'value' => 25,  'icon' => 'fas fa-globe-asia',     'sort_order' => 1],
            ['label_lo' => 'ພຣະສົງສາກົນ',    'label_en' => 'International Monks', 'label_zh' => '国际僧侣',  'value' => 500, 'icon' => 'fas fa-hands-praying',  'sort_order' => 2],
            ['label_lo' => 'ກິດຈະກໍາ / ປີ',  'label_en' => 'Events per Year',    'label_zh' => '每年活动',  'value' => 120, 'icon' => 'fas fa-calendar-check', 'sort_order' => 3],
            ['label_lo' => 'ປີດໍາເນີນການ',   'label_en' => 'Years of Operation', 'label_zh' => '运营年数',  'value' => 40,  'icon' => 'fas fa-dharmachakra',   'sort_order' => 4],
        ];

        foreach ($stats as $stat) {
            SiteStatistic::firstOrCreate(['label_en' => $stat['label_en']], $stat);
        }
    }
}
