<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_lo' => 'ຂ່າວອພສ',          'name_en' => 'BFOL News',            'name_zh' => '佛教新闻',   'slug' => 'bfol-news',       'type' => 'news',     'color' => '#1a3a6b', 'sort_order' => 1],
            ['name_lo' => 'ການທູດ',             'name_en' => 'Diplomacy',             'name_zh' => '宗教外交',   'slug' => 'diplomacy',       'type' => 'news',     'color' => '#8B1A1A', 'sort_order' => 2],
            ['name_lo' => 'DhammaOnLen',         'name_en' => 'DhammaOnLen',           'name_zh' => 'DhammaOnLen','slug' => 'dhammaonlen',    'type' => 'news',     'color' => '#6b5a1a', 'sort_order' => 3],
            ['name_lo' => 'ກິດຈະກໍາ',          'name_en' => 'Activities',            'name_zh' => '活动',      'slug' => 'activities',      'type' => 'news',     'color' => '#1a6b3a', 'sort_order' => 4],
            ['name_lo' => 'ສາດສະໜາ',          'name_en' => 'Religion',              'name_zh' => '宗教',      'slug' => 'religion',        'type' => 'news',     'color' => '#4a1a6b', 'sort_order' => 5],
            ['name_lo' => 'ກິດຈະກໍາສາກົນ',   'name_en' => 'International Events', 'name_zh' => '国际活动',   'slug' => 'intl-events',     'type' => 'event',    'color' => '#1a3a6b', 'sort_order' => 1],
            ['name_lo' => 'ກິດຈະກໍາພາຍໃນ',  'name_en' => 'Domestic Events',      'name_zh' => '国内活动',   'slug' => 'domestic-events', 'type' => 'event',    'color' => '#1a6b3a', 'sort_order' => 2],
            ['name_lo' => 'ວິດີໂອທໍາ',          'name_en' => 'Dhamma Videos',        'name_zh' => '佛法视频',   'slug' => 'dhamma-videos',   'type' => 'media',    'color' => '#6b5a1a', 'sort_order' => 1],
            ['name_lo' => 'ຮູບພາບກິດຈະກໍາ',  'name_en' => 'Event Photos',         'name_zh' => '活动照片',   'slug' => 'event-photos',    'type' => 'media',    'color' => '#1a3a6b', 'sort_order' => 2],
            ['name_lo' => 'ທໍາສຽງ',              'name_en' => 'Dhamma Audio',         'name_zh' => '佛法音频',   'slug' => 'dhamma-audio',    'type' => 'media',    'color' => '#4a1a6b', 'sort_order' => 3],
            ['name_lo' => 'ເອກະສານ MOU',       'name_en' => 'MOU Documents',        'name_zh' => 'MOU文件',   'slug' => 'mou-documents',   'type' => 'document', 'color' => '#8B1A1A', 'sort_order' => 1],
            ['name_lo' => 'ລາຍງານ',              'name_en' => 'Reports',              'name_zh' => '报告',      'slug' => 'reports',         'type' => 'document', 'color' => '#1a5f6b', 'sort_order' => 2],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
