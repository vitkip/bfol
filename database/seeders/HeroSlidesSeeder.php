<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlidesSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'tag_lo' => 'ອພສສູນກາງ', 'tag_en' => 'BFOL Central', 'tag_zh' => '老挝佛教联合会中央',
                'title_lo' => 'ກັມມາທິການການຕ່າງປະເທດສູນກາງອພສ',
                'title_en' => 'BFOL Foreign Affairs Committee',
                'title_zh' => '老挝佛教联合会中央对外委员会',
                'subtitle_lo' => 'ສົ່ງເສີມການທູດສາດສະໜາແລະການຮ່ວມມືທາງທໍາ',
                'subtitle_en' => 'Promoting religious diplomacy and Dhamma cooperation',
                'subtitle_zh' => '促进宗教外交与法的国际合作',
                'image_url' => '/uploads/slides/slide-1.jpg',
                'btn1_text_lo' => 'ກ່ຽວກັບອພສ', 'btn1_text_en' => 'About BFOL', 'btn1_text_zh' => '关于佛教联合会', 'btn1_url' => '#about',
                'btn2_text_lo' => 'DhammaOnLen',   'btn2_text_en' => 'DhammaOnLen', 'btn2_text_zh' => 'DhammaOnLen',   'btn2_url' => 'https://www.facebook.com/DhammaOnLen',
                'sort_order' => 1, 'is_active' => true,
            ],
            [
                'tag_lo' => 'ການທູດສາດສະໜາ', 'tag_en' => 'Religious Diplomacy', 'tag_zh' => '宗教外交',
                'title_lo' => 'ຮ່ວມມືສາກົນເພື່ອຄໍາສອນພຣະພຸດທ໌',
                'title_en' => 'International Cooperation for Dhamma',
                'title_zh' => '国际合作弘扬佛陀教义',
                'subtitle_lo' => 'ຮ່ວມມືກັບອົງການພຸດທ໌ສາດສາກົນຫຼາຍກ່ວາ 20 ປະເທດ',
                'subtitle_en' => 'Partnering with Buddhist organizations in over 20 countries',
                'subtitle_zh' => '与20多个国家的佛教机构合作',
                'image_url' => '/uploads/slides/slide-2.jpg',
                'btn1_text_lo' => 'ລາຍລະອຽດ', 'btn1_text_en' => 'Details',  'btn1_text_zh' => '详情',   'btn1_url' => '#mission',
                'btn2_text_lo' => 'ຄູ່ຮ່ວມມື', 'btn2_text_en' => 'Partners', 'btn2_text_zh' => '合作伙伴', 'btn2_url' => '#partners',
                'sort_order' => 2, 'is_active' => true,
            ],
            [
                'tag_lo' => 'DhammaOnLen', 'tag_en' => 'DhammaOnLen', 'tag_zh' => 'DhammaOnLen',
                'title_lo' => 'ຟັງທໍາຮຽນທໍາໃນຍຸກດິຈິຕອນ',
                'title_en' => 'Dhamma in the Digital Age',
                'title_zh' => '数字时代聆听佛法',
                'subtitle_lo' => 'ເຊື່ອມຕໍ່ຄໍາສອນພຣະພຸດທ໌ຜ່ານ Online',
                'subtitle_en' => 'Connecting Dhamma teachings online',
                'subtitle_zh' => '通过互联网传播佛陀教义',
                'image_url' => '/uploads/slides/slide-3.jpg',
                'btn1_text_lo' => 'ຕິດຕາມ Facebook', 'btn1_text_en' => 'Follow Facebook', 'btn1_text_zh' => '关注Facebook', 'btn1_url' => 'https://www.facebook.com/DhammaOnLen',
                'btn2_text_lo' => 'ຮຽນຮູ້ເພີ່ມ',    'btn2_text_en' => 'Learn More',       'btn2_text_zh' => '了解更多',     'btn2_url' => '#media',
                'sort_order' => 3, 'is_active' => true,
            ],
        ];

        foreach ($slides as $i => $slide) {
            HeroSlide::firstOrCreate(['sort_order' => $slide['sort_order']], $slide);
        }
    }
}
