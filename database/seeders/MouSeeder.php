<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MouSeeder extends Seeder
{
    public function run(): void
    {
        // Get partner IDs
        $partners = DB::table('partner_organizations')->pluck('id')->toArray();
        $p1 = $partners[0] ?? null;
        $p2 = $partners[1] ?? null;
        $p3 = $partners[2] ?? null;

        $mous = [
            [
                'title_lo'      => 'ບົດ ບັນ ທຶກ ຄວາມ ເຂົ້າ ໃຈ ດ້ານ ການ ສຶກ ສາ ສາ ສະ ໜາ',
                'title_en'      => 'MOU on Buddhist Education Cooperation',
                'title_zh'      => '佛教教育合作諒解備忘錄',
                'partner_org_id'=> $p2,
                'signed_date'   => '2022-03-15',
                'expiry_date'   => '2027-03-14',
                'status'        => 'active',
                'description_lo'=> 'ຄວາມ ຮ່ວມ ມື ດ້ານ ການ ສຶກ ສາ ສາ ສະ ໜາ ລະ ຫວ່າງ ອພສ ສູນ ກາງ ແລະ ມ ຈ ຣ ປ ກ ທ ໄທ.',
                'description_en'=> 'Cooperation agreement on Buddhist education between BFOL and Mahachulalongkornrajavidyalaya University, Thailand.',
                'description_zh'=> '老撾佛協與泰國摩訶朱拉隆功大學之間的佛教教育合作協議。',
                'signers_lo'    => 'ທ່ານ ທ່ານ ມະ ຫາ ບຸນ ໂສ ມ ຈ ລາວ',
                'signers_en'    => 'Most Ven. Bounthom — BFOL; Rector — MCU Thailand',
                'scope_lo'      => 'ການ ແລກ ປ່ຽນ ນັກ ສຶກ ສາ, ຄູ ສອນ ແລະ ສື່ ການ ສຶກ ສາ ດ້ານ ສາ ສະ ໜາ',
                'scope_en'      => 'Exchange of students, teachers, and Buddhist educational materials',
            ],
            [
                'title_lo'      => 'ບົດ ບັນ ທຶກ ຄວາມ ເຂົ້າ ໃຈ ດ້ານ ການ ຄົ້ນ ຄ້ວາ ວິ ໄຊ',
                'title_en'      => 'MOU on Buddhist Research Collaboration',
                'title_zh'      => '佛教學術研究合作諒解備忘錄',
                'partner_org_id'=> $p3,
                'signed_date'   => '2023-07-01',
                'expiry_date'   => '2026-06-30',
                'status'        => 'active',
                'description_lo'=> 'ຂໍ ຕົກ ລົງ ຮ່ວມ ມື ກ່ຽວ ກັບ ການ ຄົ້ນ ຄ້ວາ ວິ ໄຊ ດ້ານ ສາ ສະ ໜາ ລາວ.',
                'description_en'=> 'Agreement for joint research on Lao Buddhism, manuscript preservation, and academic exchange.',
                'description_zh'=> '關於老撾佛教研究、文獻保護與學術交流的合作協議。',
                'signers_lo'    => 'ຕາງ ໜ້າ ອພສ ແລະ ສ ນ ຊ',
                'signers_en'    => 'Representatives of BFOL and National University of Laos',
                'scope_lo'      => 'ການ ຄົ້ນ ຄ້ວາ ຮ່ວມ ກັນ ດ້ານ ສາ ສະ ໜາ ໂບ ຮານ ລາວ',
                'scope_en'      => 'Joint research on ancient Lao Buddhism and manuscript digitisation',
            ],
            [
                'title_lo'      => 'ບົດ ບັນ ທຶກ ຄວາມ ເຂົ້າ ໃຈ ດ້ານ ການ ສົ່ງ ເສີມ ສາ ສະ ໜາ ສາ ກົນ',
                'title_en'      => 'MOU on International Buddhist Promotion',
                'title_zh'      => '國際佛教推廣諒解備忘錄',
                'partner_org_id'=> $p2,
                'signed_date'   => '2021-11-20',
                'expiry_date'   => '2024-11-19',
                'status'        => 'expired',
                'description_lo'=> 'ຂໍ ຕົກ ລົງ ຮ່ວມ ມື ໃນ ການ ສົ່ງ ເສີມ ສາ ສະ ໜາ ລາວ ໃນ ລະ ດັບ ສາ ກົນ.',
                'description_en'=> 'Agreement to jointly promote Lao Buddhism on the international stage through cultural exchange and publications.',
                'description_zh'=> '通過文化交流和出版物在國際舞台上共同推廣老撾佛教的協議。',
                'signers_lo'    => 'ຕາງ ໜ້າ ອົງ ການ ທັງ ສອງ',
                'signers_en'    => 'Representatives of both organisations',
                'scope_lo'      => 'ການ ສົ່ງ ເສີມ ສາ ສະ ໜາ ລາວ ລະ ດັບ ສາ ກົນ',
                'scope_en'      => 'International promotion of Lao Buddhism',
            ],
            [
                'title_lo'      => 'ບົດ ບັນ ທຶກ ຄວາມ ເຂົ້າ ໃຈ ດ້ານ ການ ຊ່ວຍ ເຫຼືອ ສັງ ຄົມ',
                'title_en'      => 'MOU on Community Development and Aid',
                'title_zh'      => '社區發展與援助諒解備忘錄',
                'partner_org_id'=> $p1,
                'signed_date'   => '2024-01-10',
                'expiry_date'   => '2029-01-09',
                'status'        => 'active',
                'description_lo'=> 'ຂໍ ຕົກ ລົງ ດ້ານ ການ ຊ່ວຍ ເຫຼືອ ພັດ ທະ ນາ ຊຸມ ຊົນ ໃນ ເຂດ ຫ່າງ ໄກ.',
                'description_en'=> 'Agreement for community development assistance and social welfare projects in remote areas of Laos.',
                'description_zh'=> '老撾偏遠地區社區發展援助與社會福利項目協議。',
                'signers_lo'    => 'ຕາງ ໜ້າ ອພສ',
                'signers_en'    => 'BFOL Representative',
                'scope_lo'      => 'ການ ຊ່ວຍ ເຫຼືອ ຊຸມ ຊົນ ໃນ ເຂດ ຫ່າງ ໄກ',
                'scope_en'      => 'Community aid and development in remote areas',
            ],
            [
                'title_lo'      => 'ບົດ ບັນ ທຶກ ຄວາມ ເຂົ້າ ໃຈ ດ້ານ ການ ແລກ ປ່ຽນ ວັດ ທະ ນະ ທຳ',
                'title_en'      => 'MOU on Cultural Exchange Programme',
                'title_zh'      => '文化交流項目諒解備忘錄',
                'partner_org_id'=> $p3,
                'signed_date'   => '2025-04-05',
                'expiry_date'   => '2028-04-04',
                'status'        => 'active',
                'description_lo'=> 'ຂໍ ຕົກ ລົງ ດ້ານ ການ ແລກ ປ່ຽນ ວັດ ທະ ນະ ທຳ ລາວ — ຕ່າງ ປະ ເທດ.',
                'description_en'=> 'Agreement for cultural exchange between Lao and international Buddhist organisations including monk visits and art exhibitions.',
                'description_zh'=> '老撾與國際佛教組織之間的文化交流協議，包括僧侶互訪和藝術展覽。',
                'signers_lo'    => 'ຕາງ ໜ້າ ອົງ ການ ທັງ ສອງ',
                'signers_en'    => 'Representatives of both organisations',
                'scope_lo'      => 'ການ ແລກ ປ່ຽນ ວັດ ທະ ນະ ທຳ ລາວ ແລະ ສາ ກົນ',
                'scope_en'      => 'Lao and international cultural exchange',
            ],
        ];

        foreach ($mous as $mou) {
            DB::table('mou_agreements')->insert(array_merge($mou, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
