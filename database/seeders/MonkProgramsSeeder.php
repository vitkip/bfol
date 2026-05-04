<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonkProgramsSeeder extends Seeder
{
    public function run(): void
    {
        $partners = DB::table('partner_organizations')->pluck('id')->toArray();
        $p1 = $partners[0] ?? null;
        $p2 = $partners[1] ?? null;
        $p3 = $partners[2] ?? null;

        $programs = [
            [
                'title_lo'          => 'ໂຄງ ການ ແລກ ປ່ຽນ ສາ ມະ ເນ ນ ໄທ — ລາວ',
                'title_en'          => 'Thailand–Laos Novice Monk Exchange',
                'title_zh'          => '泰老沙彌交流項目',
                'destination_country'=> 'Thailand',
                'partner_org_id'    => $p2,
                'year'              => 2025,
                'application_open'  => '2025-01-15',
                'application_deadline'=> '2025-03-31',
                'program_start'     => '2025-06-01',
                'program_end'       => '2025-11-30',
                'monks_quota'       => 20,
                'monks_selected'    => 18,
                'description_lo'    => 'ໂຄງ ການ ສົ່ງ ສາ ມະ ເນ ນ ລາວ ໄປ ຮຽນ ພຣະ ທຳ ຢູ່ ປະ ເທດ ໄທ ເປັນ ໄລ ຍະ 6 ເດືອນ ໂດຍ ໄດ້ ຮັບ ການ ສຶກ ສາ ດ້ານ ພາ ສາ ໄທ ແລະ ຄຳ ສອນ ທາງ ທັດ ທະ.',
                'description_en'    => 'A six-month programme sending Lao novice monks to study Dhamma in Thailand, covering Thai language, Buddhist teachings, and cultural immersion.',
                'description_zh'    => '為期六個月的項目，派遣老撾沙彌赴泰國學習佛法，涵蓋泰語、佛教教義和文化體驗。',
                'requirements_lo'   => 'ສາ ມະ ເນ ນ ອາ ຍຸ 15-25 ປີ, ຈົບ ຊັ້ນ ມ 3, ສຸ ຂະ ພາ ບ ດີ',
                'requirements_en'   => 'Novice monks aged 15–25, completed Grade 9, good health and conduct',
                'contact_email'     => 'exchange@bfol.la',
                'status'            => 'ongoing',
                'is_featured'       => true,
            ],
            [
                'title_lo'          => 'ໂຄງ ການ ຮຽນ ສາ ສະ ໜາ ຢູ່ ອິນ ເດຍ',
                'title_en'          => 'India Buddhist Studies Programme',
                'title_zh'          => '印度佛教研修項目',
                'destination_country'=> 'India',
                'partner_org_id'    => $p1,
                'year'              => 2026,
                'application_open'  => '2025-10-01',
                'application_deadline'=> '2026-01-31',
                'program_start'     => '2026-04-01',
                'program_end'       => '2027-03-31',
                'monks_quota'       => 10,
                'monks_selected'    => 0,
                'description_lo'    => 'ໂຄງ ການ ສົ່ງ ພຣະ ສົງ ລາວ ໄປ ສຶກ ສາ ຢູ່ ສະ ຖາ ບັນ ສາ ສະ ໜາ ໃນ ອິນ ເດຍ ປະ ກອບ ທັງ ການ ຢ້ຽມ ຢາມ ສະ ຖານ ທີ່ ສັກ ສິດ.',
                'description_en'    => 'A one-year programme for Lao monks to study at Buddhist institutions in India, including pilgrimage to sacred Buddhist sites.',
                'description_zh'    => '為期一年的項目，派遣老撾僧侶在印度佛教機構學習，包括朝聖佛教聖地。',
                'requirements_lo'   => 'ພຣະ ສົງ ອາ ຍຸ 25-45 ປີ, ບວດ ມາ ຢ່າງ ໜ້ອຍ 5 ວັສ ສາ, ພາ ສາ ອັງ ກິດ ລະ ດັບ ພື້ນ ຖານ',
                'requirements_en'   => 'Monks aged 25–45, minimum 5 years ordained, basic English proficiency',
                'contact_email'     => 'india@bfol.la',
                'status'            => 'open',
                'is_featured'       => true,
            ],
            [
                'title_lo'          => 'ໂຄງ ການ ແລກ ປ່ຽນ ວັດ ທະ ນະ ທຳ ລາວ — ຈີນ',
                'title_en'          => 'Laos–China Buddhist Cultural Exchange',
                'title_zh'          => '老中佛教文化交流項目',
                'destination_country'=> 'China',
                'partner_org_id'    => $p3,
                'year'              => 2024,
                'application_open'  => '2024-02-01',
                'application_deadline'=> '2024-04-30',
                'program_start'     => '2024-07-15',
                'program_end'       => '2024-12-15',
                'monks_quota'       => 15,
                'monks_selected'    => 15,
                'description_lo'    => 'ໂຄງ ການ ຮ່ວມ ມື ດ້ານ ວັດ ທະ ນະ ທຳ ສາ ສະ ໜາ ລາວ ແລະ ຈີນ ລວມ ທັງ ການ ທ່ຽວ ຊົມ ວັດ ທີ່ ສຳ ຄັນ ໃນ ຈີນ.',
                'description_en'    => 'Buddhist cultural exchange between Laos and China, including visits to major Buddhist temples and institutions across China.',
                'description_zh'    => '老中佛教文化交流，包括參訪中國各地主要佛教寺廟和機構。',
                'requirements_lo'   => 'ພຣະ ສົງ ແລະ ສາ ມະ ເນ ນ, ສຸ ຂະ ພາ ບ ດີ',
                'requirements_en'   => 'Monks and novices in good health',
                'contact_email'     => 'china@bfol.la',
                'status'            => 'closed',
                'is_featured'       => false,
            ],
            [
                'title_lo'          => 'ໂຄງ ການ ຝຶກ ວິ ປັດ ສະ ນາ ຢູ່ ມຽນ ມາ',
                'title_en'          => 'Myanmar Vipassana Meditation Programme',
                'title_zh'          => '緬甸內觀禪修研修項目',
                'destination_country'=> 'Myanmar',
                'partner_org_id'    => $p2,
                'year'              => 2026,
                'application_open'  => '2025-11-01',
                'application_deadline'=> '2026-02-28',
                'program_start'     => '2026-05-01',
                'program_end'       => '2026-07-31',
                'monks_quota'       => 12,
                'monks_selected'    => 0,
                'description_lo'    => 'ໂຄງ ການ ໄປ ຝຶກ ວິ ປັດ ສະ ນາ ແລະ ສະ ມາ ທິ ຢູ່ ສູນ ວິ ປັດ ສະ ນາ ທີ່ ມີ ຊື່ ສຽງ ໃນ ມຽນ ມາ.',
                'description_en'    => 'Intensive Vipassana and meditation training at renowned meditation centres in Myanmar under experienced teachers.',
                'description_zh'    => '在緬甸著名禪修中心接受經驗豐富的教師指導，進行密集的內觀和禪修訓練。',
                'requirements_lo'   => 'ພຣະ ສົງ ຫຼື ສາ ມະ ເນ ນ ທີ່ ຕ້ອງ ການ ເລິກ ເຊິ່ງ ໃນ ການ ຝຶກ ສະ ມາ ທິ',
                'requirements_en'   => 'Monks or novices with genuine interest in deepening meditation practice',
                'contact_email'     => 'myanmar@bfol.la',
                'status'            => 'open',
                'is_featured'       => true,
            ],
            [
                'title_lo'          => 'ໂຄງ ການ ສຶກ ສາ ສາ ສະ ໜາ ຢູ່ ສ ຣີ ລັງ ກາ',
                'title_en'          => 'Sri Lanka Theravada Studies Programme',
                'title_zh'          => '斯里蘭卡上座部佛教研修',
                'destination_country'=> 'Sri Lanka',
                'partner_org_id'    => $p1,
                'year'              => 2025,
                'application_open'  => '2025-03-01',
                'application_deadline'=> '2025-05-31',
                'program_start'     => '2025-08-01',
                'program_end'       => '2026-07-31',
                'monks_quota'       => 8,
                'monks_selected'    => 7,
                'description_lo'    => 'ການ ສຶກ ສາ ໂດຍ ກົງ ດ້ານ ສາ ສະ ໜາ ເທ ຣ ວາ ດາ ຢູ່ ສ ຣີ ລັງ ກາ ເຊິ່ງ ເປັນ ປ ຣ ະ ເທດ ທີ່ ມີ ປ ຣ ະ ເພ ນີ ສາ ສະ ໜາ ໂຊ ກ ດີ ທີ່ ສຸດ.',
                'description_en'    => 'Direct study of Theravada Buddhism in Sri Lanka, one of the world\'s most important Theravada Buddhist nations, at leading institutions.',
                'description_zh'    => '在斯里蘭卡（世界最重要的上座部佛教國家之一）的頂尖機構直接學習上座部佛教。',
                'requirements_lo'   => 'ພຣະ ສົງ ທີ່ ມີ ຄວາມ ສາ ມາດ ດ້ານ ພາ ສາ ອັງ ກິດ ໃນ ລະ ດັບ ດີ',
                'requirements_en'   => 'Monks with good English language proficiency',
                'contact_email'     => 'srilanka@bfol.la',
                'status'            => 'ongoing',
                'is_featured'       => false,
            ],
        ];

        // Keep existing record
        $existing = DB::table('monk_exchange_programs')->count();
        $toInsert = $existing > 0 ? array_slice($programs, 1) : $programs;

        foreach ($toInsert as $program) {
            DB::table('monk_exchange_programs')->insert(array_merge($program, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
