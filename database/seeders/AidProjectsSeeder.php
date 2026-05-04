<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AidProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $partners = DB::table('partner_organizations')->pluck('id')->toArray();
        $p1 = $partners[0] ?? null;
        $p2 = $partners[1] ?? null;
        $p3 = $partners[2] ?? null;

        $projects = [
            [
                'title_lo'       => 'ໂຄງ ການ ກໍ່ ສ້າງ ໂຮງ ຮຽນ ສາ ສະ ໜາ ເຂດ ຊົນ ນະ ບົດ',
                'title_en'       => 'Rural Buddhist School Construction Project',
                'title_zh'       => '農村佛教學校建設項目',
                'country'        => 'ລາວ',
                'partner_org_id' => $p2,
                'type'           => 'educational',
                'description_lo' => 'ໂຄງ ການ ກໍ່ ສ້າງ ແລະ ສ້ອມ ແປງ ໂຮງ ຮຽນ ສາ ສະ ໜາ ໃນ ເຂດ ຊົນ ນະ ບົດ ຂອງ ສປ ລາວ ໂດຍ ສະ ເພາະ ໃນ ແຂວງ ຫ່າງ ໄກ.',
                'description_en' => 'Construction and renovation of Buddhist schools in rural areas of Lao PDR, particularly in remote provinces to improve access to religious education.',
                'description_zh' => '在老撾偏遠省份農村地區建設和翻新佛教學校，改善宗教教育的可及性。',
                'budget_usd'     => 85000,
                'start_date'     => '2023-06-01',
                'end_date'       => '2025-05-31',
                'status'         => 'active',
            ],
            [
                'title_lo'       => 'ໂຄງ ການ ໃຫ້ ທຶນ ນັກ ສຶກ ສາ ສາ ສະ ໜາ',
                'title_en'       => 'Buddhist Student Scholarship Programme',
                'title_zh'       => '佛教學生獎學金項目',
                'country'        => 'ລາວ',
                'partner_org_id' => $p1,
                'type'           => 'educational',
                'description_lo' => 'ໂຄງ ການ ໃຫ້ ທຶນ ສຳ ລັບ ສາ ມະ ເນ ນ ແລະ ພ້ອຍ ທີ່ ຕ້ອງ ການ ໄປ ສຶກ ສາ ຕໍ່ ໃນ ຕ່າງ ປະ ເທດ.',
                'description_en' => 'Scholarship programme for novice monks and lay students to pursue advanced Buddhist studies in Thailand, Myanmar, and India.',
                'description_zh' => '為沙彌和在家學生在泰國、緬甸和印度深造佛教學的獎學金計劃。',
                'budget_usd'     => 45000,
                'start_date'     => '2024-01-01',
                'end_date'       => '2026-12-31',
                'status'         => 'active',
            ],
            [
                'title_lo'       => 'ໂຄງ ການ ຊ່ວຍ ເຫຼືອ ຜູ້ ຖືກ ພະ ຍາ ດ ທຳ ມະ ຊາດ',
                'title_en'       => 'Natural Disaster Relief & Recovery Project',
                'title_zh'       => '自然災害救援與恢復項目',
                'country'        => 'ລາວ',
                'partner_org_id' => $p3,
                'type'           => 'humanitarian',
                'description_lo' => 'ການ ຊ່ວຍ ເຫຼືອ ຊຸມ ຊົນ ທີ່ ຖືກ ກ ະ ທົບ ຈາກ ໄພ ນຳ ຖ້ວມ ໃນ ພາກ ໃຕ້ ຂອງ ລາວ ໃນ ປີ 2022.',
                'description_en' => 'Emergency relief and community recovery support for flood-affected communities in southern Laos, including food, shelter, and psychological support.',
                'description_zh' => '為老撾南部洪水受災社區提供緊急救援和社區恢復支持，包括食物、住所和心理援助。',
                'budget_usd'     => 32000,
                'start_date'     => '2022-08-01',
                'end_date'       => '2023-03-31',
                'status'         => 'completed',
            ],
            [
                'title_lo'       => 'ໂຄງ ການ ສ້າງ ວັດ ໃໝ່ ໃນ ເຂດ ຊາຍ ແດນ',
                'title_en'       => 'Border Area Temple Construction',
                'title_zh'       => '邊境地區寺廟建設項目',
                'country'        => 'ລາວ',
                'partner_org_id' => $p2,
                'type'           => 'religious',
                'description_lo' => 'ໂຄງ ການ ກໍ່ ສ້າງ ວັດ ໃໝ່ ໃນ ບ້ານ ຊາຍ ແດນ ທີ່ ຂາດ ສະ ຖານ ທີ່ ປະ ຕິ ບັດ ສາ ສະ ໜາ.',
                'description_en' => 'Construction of new temple buildings in border villages lacking proper Buddhist worship facilities, serving remote communities.',
                'description_zh' => '在缺乏佛教禮拜場所的邊境村莊建設新寺廟，服務偏遠社區。',
                'budget_usd'     => 120000,
                'start_date'     => '2025-03-01',
                'end_date'       => '2027-02-28',
                'status'         => 'active',
            ],
            [
                'title_lo'       => 'ໂຄງ ການ ສາ ທາ ລະ ນະ ສຸກ ໃນ ຊຸມ ຊົນ ວັດ',
                'title_en'       => 'Temple Community Health Programme',
                'title_zh'       => '寺廟社區健康計劃',
                'country'        => 'ລາວ',
                'partner_org_id' => $p1,
                'type'           => 'humanitarian',
                'description_lo' => 'ໂຄງ ການ ສ້າງ ຄວາມ ຮູ້ ດ້ານ ສຸ ຂະ ພາ ບ ໂດຍ ໃຊ້ ວັດ ເປັນ ສູນ ກາງ ໃນ ຊຸມ ຊົນ ຊົນ ນະ ບົດ.',
                'description_en' => 'Community health awareness and basic healthcare delivery using temples as outreach centres in rural communities of Laos.',
                'description_zh' => '以寺廟為推廣中心，在老撾農村社區開展社區健康教育和基本醫療服務。',
                'budget_usd'     => 28000,
                'start_date'     => '2023-09-01',
                'end_date'       => '2025-08-31',
                'status'         => 'active',
            ],
            [
                'title_lo'       => 'ໂຄງ ການ ອະ ນຸ ລັກ ຊັບ ສິນ ທາງ ສາ ສະ ໜາ',
                'title_en'       => 'Buddhist Heritage Preservation Project',
                'title_zh'       => '佛教文化遺產保護項目',
                'country'        => 'ລາວ',
                'partner_org_id' => $p3,
                'type'           => 'cultural',
                'description_lo' => 'ໂຄງ ການ ອະ ນຸ ລັກ ແລະ ດິ ຈິ ຕໍ ລ າ ໄຊ ສ໌ ໜັງ ສື ໃບ ລານ ທາງ ສາ ສະ ໜາ ໂບ ຮານ ຂອງ ລາວ.',
                'description_en' => 'Preservation and digitisation of ancient Lao Buddhist palm leaf manuscripts to protect irreplaceable religious heritage.',
                'description_zh' => '保護和數字化老撾古代佛教貝葉經，守護不可替代的宗教文化遺產。',
                'budget_usd'     => 55000,
                'start_date'     => '2024-06-01',
                'end_date'       => '2026-05-31',
                'status'         => 'active',
            ],
        ];

        // Keep existing record, add only new ones
        if (DB::table('aid_projects')->count() < 2) {
            foreach ($projects as $project) {
                DB::table('aid_projects')->insert(array_merge($project, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        } else {
            // Just insert missing ones (skip first since it exists)
            foreach (array_slice($projects, 1) as $project) {
                DB::table('aid_projects')->insert(array_merge($project, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
