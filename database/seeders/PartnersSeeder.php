<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnersSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name_lo'          => 'ອົງ ການ ສາ ສະ ໜາ ທົ່ວ ໂລກ ພຸດ ທະ',
                'name_en'          => 'World Fellowship of Buddhists',
                'name_zh'          => '世界佛教徒友誼會',
                'acronym'          => 'WFB',
                'country_code'     => 'TH',
                'country_name_lo'  => 'ໄທ',
                'country_name_en'  => 'Thailand',
                'country_name_zh'  => '泰國',
                'website_url'      => 'https://www.wfb-hq.org',
                'description_lo'   => 'ອົງ ການ ສາ ສະ ໜາ ພຸດ ທະ ລະ ດັບ ສາ ກົນ ທີ່ ໃຫຍ່ ທີ່ ສຸດ ໃນ ໂລກ ທີ່ ມີ ສຳ ນັກ ງານ ໃຫຍ່ ໃນ ກຸງ ເທດ ທາ ມະ ໄທ.',
                'description_en'   => 'The World Fellowship of Buddhists is the largest international Buddhist organisation in the world, headquartered in Bangkok, Thailand.',
                'description_zh'   => '世界佛教徒友誼會是全球最大的國際佛教組織，總部設於泰國曼谷。',
                'type'             => 'buddhist_org',
                'partnership_since'=> '2015',
                'status'           => 'active',
                'sort_order'       => 1,
            ],
            [
                'name_lo'          => 'ສະ ມາ ຄົມ ພຸດ ທະ ໂລ ກ ລາວ',
                'name_en'          => 'Buddhist Association of China',
                'name_zh'          => '中國佛教協會',
                'acronym'          => 'BAC',
                'country_code'     => 'CN',
                'country_name_lo'  => 'ຈີນ',
                'country_name_en'  => 'China',
                'country_name_zh'  => '中國',
                'website_url'      => 'https://www.chinabuddhism.com.cn',
                'description_lo'   => 'ສະ ມາ ຄົມ ສາ ສະ ໜາ ພຸດ ທະ ທີ່ ໃຫຍ່ ທີ່ ສຸດ ໃນ ສາ ທາ ລະ ນະ ລັດ ປ ຣ ະ ຊາ ຊົນ ຈີນ.',
                'description_en'   => 'The Buddhist Association of China is the largest national Buddhist organisation in the People\'s Republic of China.',
                'description_zh'   => '中國佛教協會是中華人民共和國最大的全國性佛教組織。',
                'type'             => 'buddhist_org',
                'partnership_since'=> '2018',
                'status'           => 'active',
                'sort_order'       => 2,
            ],
            [
                'name_lo'          => 'ສະ ຖາ ບັນ ວິ ທະ ຍາ ສາດ ສາ ສະ ໜາ ໂລ ກ',
                'name_en'          => 'International Buddhist College',
                'name_zh'          => '國際佛學院',
                'acronym'          => 'IBC',
                'country_code'     => 'TH',
                'country_name_lo'  => 'ໄທ',
                'country_name_en'  => 'Thailand',
                'country_name_zh'  => '泰國',
                'website_url'      => 'https://www.ibc.ac.th',
                'description_lo'   => 'ສະ ຖາ ບັນ ການ ສຶກ ສາ ສາ ສະ ໜາ ລະ ດັບ ສູງ ທີ່ ສະ ໜອງ ຫຼັກ ສູດ ດ້ານ ສາ ສະ ໜາ ສາ ກົນ ຢູ່ ໃນ ປ ຣ ະ ເທດ ໄທ.',
                'description_en'   => 'An international institution of higher Buddhist learning offering graduate programmes in Buddhist studies in Thailand.',
                'description_zh'   => '國際佛教高等教育機構，在泰國提供佛學研究生課程。',
                'type'             => 'academic',
                'partnership_since'=> '2019',
                'status'           => 'active',
                'sort_order'       => 3,
            ],
            [
                'name_lo'          => 'ອົງ ການ ສາ ສະ ໜາ ອາ ຊ ຽນ',
                'name_en'          => 'ASEAN Buddhist Council',
                'name_zh'          => '東盟佛教理事會',
                'acronym'          => 'ABC',
                'country_code'     => 'SG',
                'country_name_lo'  => 'ສິງ ກາ ໂປ',
                'country_name_en'  => 'Singapore',
                'country_name_zh'  => '新加坡',
                'website_url'      => null,
                'description_lo'   => 'ສະ ພາ ສາ ສະ ໜາ ຂອງ ປ ຣ ະ ເທດ ສະ ມາ ຊິກ ອາ ຊ ຽນ ທີ່ ມຸ້ງ ໝັ້ນ ໃນ ການ ເສີມ ສ້າງ ຄວາມ ໂດດ ເດັ່ນ ຂອງ ສາ ສະ ໜາ ໃນ ໄຕ ລາ ຍ ພາກ ພ ວ ມ.',
                'description_en'   => 'A regional council of Buddhist organisations from ASEAN member states, promoting Buddhist cooperation and cultural exchange throughout the region.',
                'description_zh'   => '東盟成員國佛教組織的地區理事會，致力於促進整個地區的佛教合作與文化交流。',
                'type'             => 'buddhist_org',
                'partnership_since'=> '2020',
                'status'           => 'active',
                'sort_order'       => 4,
            ],
            [
                'name_lo'          => 'ສູນ ສຶກ ສາ ສາ ສະ ໜາ ອິນ ເດຍ',
                'name_en'          => 'Nalanda Institute of Buddhist Studies',
                'name_zh'          => '那爛陀佛學研究院',
                'acronym'          => 'NIBS',
                'country_code'     => 'IN',
                'country_name_lo'  => 'ອິນ ເດຍ',
                'country_name_en'  => 'India',
                'country_name_zh'  => '印度',
                'website_url'      => null,
                'description_lo'   => 'ສູນ ສຶກ ສາ ສາ ສະ ໜາ ທີ່ ຕັ້ງ ຢູ່ ໃກ້ ກັບ ສະ ຖານ ທີ່ ໂຮງ ຮຽນ ນາ ລາ ນ ດາ ໂບ ຮານ ໃນ ອິນ ເດຍ.',
                'description_en'   => 'A Buddhist studies institute located near the ancient Nalanda University site in Bihar, India, offering traditional and modern Buddhist education.',
                'description_zh'   => '位於印度比哈爾邦古代那爛陀大學遺址附近的佛學院，提供傳統與現代佛教教育。',
                'type'             => 'academic',
                'partnership_since'=> '2021',
                'status'           => 'active',
                'sort_order'       => 5,
            ],
        ];

        foreach ($partners as $partner) {
            // Only insert if acronym doesn't already exist
            if (!DB::table('partner_organizations')->where('acronym', $partner['acronym'])->exists()) {
                DB::table('partner_organizations')->insert(array_merge($partner, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
