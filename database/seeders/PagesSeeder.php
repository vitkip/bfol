<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'        => 'sila-dhamma',
                'title_lo'    => 'ຮຽນສີລສະມາທິທັມ',
                'title_en'    => 'Sila, Samadhi & Dhamma',
                'title_zh'    => '戒定慧教育',
                'content_lo'  => '<h2>ການສຶກສາ ສີລ ສະມາທິ ທັມ</h2>
<p>ອພສ ສູນກາງ ມຸ່ງໝັ້ນ ໃນການ ສົ່ງເສີມ ການສຶກສາ ທາງ ສາສະໜາ ໂດຍ ເນັ້ນ ໃສ່ ຫຼັກ ສີລ ສະມາທິ ທັມ ເຊິ່ງ ເປັນ ແກ່ນ ຂອງ ຄຳສອນ ທາງ ພຣະ ພຸດທະ ສາສະໜາ.</p>
<h2>ຫຼັກສູດ ການ ຮຽນ</h2>
<p>ຫຼັກສູດ ຂອງ ພວກ ເຮົາ ປະ ກອບ ມີ:</p>
<ul>
<li>ສີລ — ການ ຮັກສາ ສິນ ທຳ ແລະ ລະ ບຽບ ວິໄນ</li>
<li>ສະມາທິ — ການ ຝຶກ ສະ ມາ ທິ ແລະ ວິ ປັດ ສະ ນາ</li>
<li>ທັມ — ການ ສຶກ ສາ ຄຳ ສອນ ທາງ ທັດ ທະ</li>
</ul>
<h2>ສະ ຖານ ທີ່ ສອນ</h2>
<p>ການ ສຶກ ສາ ດຳ ເນີນ ຢູ່ ທີ່ ວັດ ວາ ອາ ຮາມ ທ່ົວ ປ່ວາ ສປ ລາວ ລວມ ທັງ ການ ສົ່ງ ນັກ ສຶກ ສາ ໄປ ຮຽນ ຢູ່ ຕ່າງ ປະ ເທດ ດ້ວຍ ສາ ສົ່ງ ນັກ ສຶກ ສາ ໄປ ທີ່ ປະ ເທດ ໄທ ສ້ຽງ ແລະ ອິນ ເດຍ.</p>',
                'content_en'  => '<h2>Sila, Samadhi & Dhamma Education</h2>
<p>BFOL is committed to promoting Buddhist education focusing on the three pillars of Sila (morality), Samadhi (meditation), and Dhamma (teachings), which form the core of Buddhist practice.</p>
<h2>Curriculum</h2>
<p>Our curriculum covers:</p>
<ul>
<li>Sila — Moral discipline and ethical conduct</li>
<li>Samadhi — Meditation practice and mindfulness</li>
<li>Dhamma — Study of Buddhist teachings and philosophy</li>
</ul>
<h2>Study Locations</h2>
<p>Education is conducted at monasteries throughout Lao PDR, with opportunities to study abroad in Thailand, Myanmar, and India through exchange programs.</p>',
                'content_zh'  => '<h2>戒定慧教育</h2>
<p>老撾佛協致力於推廣以戒（道德）、定（禪修）、慧（智慧）三學為核心的佛教教育。</p>
<h2>課程內容</h2>
<ul>
<li>戒學 — 道德規範與戒律修持</li>
<li>定學 — 禪修訓練與正念修習</li>
<li>慧學 — 佛教教義與哲學研究</li>
</ul>',
                'meta_description' => 'ການສຶກສາ ສີລ ສະມາທິ ທັມ ຂອງ ອພສ ສູນກາງ ແຫ່ງ ສປ ລາວ',
                'is_published' => true,
                'sort_order'   => 10,
            ],
            [
                'slug'        => 'teaching',
                'title_lo'    => 'ດ້ານການສອນ',
                'title_en'    => 'Teaching & Training',
                'title_zh'    => '教學與培訓',
                'content_lo'  => '<h2>ດ້ານການ ສອນ ແລະ ຝຶກ ອົບ ຮົມ</h2>
<p>ອພສ ດຳ ເນີນ ໂຄງ ການ ຝຶກ ອົບ ຮົມ ຄູ ສອນ ທາງ ສາ ສະ ໜາ ທົ່ວ ສປ ລາວ ໂດຍ ຮ່ວມ ມື ກັບ ວິ ທະ ຍາ ໄລ ສົງ ຄົນ ແລະ ໂຮງ ຮຽນ ຕ່າງໆ.</p>
<h2>ໂຄງ ການ ຝຶກ ອົບ ຮົມ</h2>
<ul>
<li>ຝຶກ ອົບ ຮົມ ຄູ ສອນ ທາງ ສາ ສະ ໜາ ລະ ດັບ ພື້ນ ຖານ</li>
<li>ຫຼັກ ສູດ ສຳ ລັບ ພຣະ ຄູ ສິດ ທິ ທ້ອງ ຖິ່ນ</li>
<li>ການ ຝຶກ ທັກ ສະ ດ້ານ ເທດ ສະ ໜາ ແລະ ການ ສື່ ສານ</li>
<li>ໂຄງ ການ ນັກ ສຶກ ສາ ດ້ານ ສາ ສະ ໜາ ລະ ດັບ ສູງ</li>
</ul>
<h2>ຜົນ ສຳ ເລັດ</h2>
<p>ອພສ ໄດ້ ຝຶກ ອົບ ຮົມ ຄູ ສອນ ທາງ ສາ ສະ ໜາ ຫຼາຍ ກວ່າ 500 ທ່ານ ທ່ົວ ສປ ລາວ ຕໍ່ ປີ.</p>',
                'content_en'  => '<h2>Teaching & Religious Education Training</h2>
<p>BFOL operates teacher training programs for religious educators across Lao PDR in cooperation with Buddhist colleges and schools nationwide.</p>
<h2>Training Programs</h2>
<ul>
<li>Basic religious education teacher training</li>
<li>Curriculum for local senior monks and teachers</li>
<li>Dhamma preaching and communication skills</li>
<li>Advanced Buddhist studies programs</li>
</ul>
<h2>Achievements</h2>
<p>BFOL trains over 500 religious teachers annually across Lao PDR, contributing significantly to the quality of Buddhist education nationwide.</p>',
                'content_zh'  => '<h2>教學與宗教培訓</h2>
<p>老撾佛協在全國各地開展宗教教師培訓計劃，與佛學院及各類學校合作推進佛教教育。</p>
<h2>培訓項目</h2>
<ul>
<li>基礎宗教教育師資培訓</li>
<li>地方高僧課程</li>
<li>弘法與溝通技巧</li>
<li>高級佛學研究項目</li>
</ul>',
                'meta_description' => 'ໂຄງການ ຝຶກ ອົບ ຮົມ ຄູ ສອນ ທາງ ສາ ສະ ໜາ ຂອງ ອພສ',
                'is_published' => true,
                'sort_order'   => 20,
            ],
            [
                'slug'        => 'research',
                'title_lo'    => 'ທັດທະ & ວິໄຊ',
                'title_en'    => 'Research & Studies',
                'title_zh'    => '學術研究',
                'content_lo'  => '<h2>ການ ຄົ້ນ ຄ້ວາ ດ້ານ ສາ ສະ ໜາ</h2>
<p>ອພສ ສຳ ລັ ດ ການ ຄົ້ນ ຄ້ວາ ວິ ໄຊ ດ້ານ ສາ ສະ ໜາ ລາວ ເຊິ່ງ ລວມ ທັງ ການ ສຶກ ສາ ປະ ຫວັດ ສາດ ສາ ສະ ໜາ, ຄຳ ສອນ ທາງ ທັດ ທະ ແລະ ການ ເຜີຍ ແຜ່ ສາ ສະ ໜາ ໃນ ລາວ.</p>
<h2>ຂົງ ເຂດ ການ ຄົ້ນ ຄ້ວາ</h2>
<ul>
<li>ປະ ຫວັດ ສາດ ສາ ສະ ໜາ ໃນ ລາວ</li>
<li>ຄຳ ສອນ ທາງ ທັດ ທະ ລາວ ສໍ ດຳ</li>
<li>ສາ ສະ ໜາ ກັບ ວັດ ທະ ນະ ທຳ ລາວ</li>
<li>ການ ປຽບ ທຽບ ສາ ສະ ໜາ ໃນ ອາ ຊ ຽ ນ</li>
</ul>
<h2>ການ ເຜີຍ ແຜ່ ຜົນ ການ ຄົ້ນ ຄ້ວາ</h2>
<p>ຜົນ ການ ຄົ້ນ ຄ້ວາ ຖືກ ເຜີຍ ແຜ່ ໃນ ວາ ລະ ສານ ສາ ສະ ໜາ ທ້ອງ ຖິ່ນ ແລະ ສາ ກົນ ພ້ອມ ທັງ ຈັດ ສໍ ມ ມ ນາ ການ ຄົ້ນ ຄ້ວາ ເປັນ ປະ ຈຳ.</p>',
                'content_en'  => '<h2>Buddhist Research & Academic Studies</h2>
<p>BFOL supports academic research on Lao Buddhism, encompassing the history of Buddhism, Dhamma teachings, and the propagation of Buddhism throughout Lao PDR.</p>
<h2>Research Areas</h2>
<ul>
<li>History of Buddhism in Laos</li>
<li>Lao Buddhist scriptures and texts</li>
<li>Buddhism and Lao cultural heritage</li>
<li>Comparative Buddhism in ASEAN</li>
</ul>
<h2>Publications & Dissemination</h2>
<p>Research findings are published in local and international Buddhist journals, with regular research seminars and academic conferences held throughout the year.</p>',
                'content_zh'  => '<h2>佛教學術研究</h2>
<p>老撾佛協積極支持佛教學術研究，涵蓋佛教歷史、教義以及佛教在老撾的傳播研究。</p>
<h2>研究領域</h2>
<ul>
<li>老撾佛教史</li>
<li>老撾佛教典籍與文本</li>
<li>佛教與老撾文化遺產</li>
<li>東盟比較佛教研究</li>
</ul>',
                'meta_description' => 'ການ ຄົ້ນ ຄ້ວາ ວິ ໄຊ ດ້ານ ສາ ສະ ໜາ ລາວ ຂອງ ອພສ',
                'is_published' => true,
                'sort_order'   => 30,
            ],
            [
                'slug'        => 'society',
                'title_lo'    => 'ສາສາ & ສັງຄົມ',
                'title_en'    => 'Religion & Society',
                'title_zh'    => '宗教與社會',
                'content_lo'  => '<h2>ສາ ສະ ໜາ ກັບ ການ ພັດ ທະ ນາ ສັງ ຄົມ</h2>
<p>ອພສ ມີ ບົດ ບາດ ສຳ ຄັນ ໃນ ການ ສົ່ງ ເສີມ ການ ພັດ ທະ ນາ ສັງ ຄົມ ໂດຍ ໃຊ້ ຫຼັກ ທຳ ຂອງ ສາ ສະ ໜາ ເປັນ ພື້ນ ຖານ.</p>
<h2>ກິດ ຈະ ກຳ ສາ ສະ ໜາ ໃນ ຊຸມ ຊົນ</h2>
<ul>
<li>ການ ຈັດ ທານ ບຸນ ແລະ ຊ່ວຍ ເຫຼືອ ຜູ້ ດ້ອຍ ໂອ ກາດ</li>
<li>ໂຄງ ການ ສ້າງ ວັດ ໃໝ່ ໃນ ເຂດ ຫ່າງ ໄກ ສ ອກ ຫຼີກ</li>
<li>ການ ຈັດ ທຳ ກິດ ຈະ ກຳ ວັດ ທະ ນະ ທຳ ແລະ ປະ ເພ ນີ</li>
<li>ການ ສ້າງ ເສີມ ຈິດ ໃຈ ແລະ ສຸ ຂະ ພາ ບ ທາງ ຈິດ</li>
</ul>
<h2>ຄຸນ ຄ່າ ທາງ ສັງ ຄົມ</h2>
<p>ວັດ ວາ ອາ ຮາມ ທ່ົວ ລາວ ທຳ ໜ້າ ທີ່ ເປັນ ສູນ ກາງ ການ ສຶກ ສາ, ການ ສາ ທາ ລະ ນະ ສຸກ ແລະ ການ ຮ່ວມ ເຮັດ ວຽກ ຂອງ ຊຸມ ຊົນ.</p>',
                'content_en'  => '<h2>Religion & Social Development</h2>
<p>BFOL plays a vital role in social development by applying Buddhist principles to promote community welfare, ethical values, and social harmony.</p>
<h2>Community Activities</h2>
<ul>
<li>Charitable giving and support for the underprivileged</li>
<li>Temple construction in remote areas</li>
<li>Cultural and traditional ceremony organisation</li>
<li>Mental wellbeing and spiritual support programs</li>
</ul>
<h2>Social Values</h2>
<p>Monasteries throughout Laos serve as centres for education, public health, and community cooperation, reinforcing the integral role of Buddhism in Lao society.</p>',
                'content_zh'  => '<h2>宗教與社會發展</h2>
<p>老撾佛協在社會發展中發揮重要作用，以佛教原則促進社區福祉、道德價值觀和社會和諧。</p>
<h2>社區活動</h2>
<ul>
<li>慈善布施與扶貧助困</li>
<li>偏遠地區寺廟建設</li>
<li>文化傳統節日活動組織</li>
<li>心理健康與精神關懷項目</li>
</ul>',
                'meta_description' => 'ບົດ ບາດ ຂອງ ສາ ສະ ໜາ ໃນ ການ ພັດ ທະ ນາ ສັງ ຄົມ ລາວ',
                'is_published' => true,
                'sort_order'   => 40,
            ],
        ];

        foreach ($pages as $page) {
            if (!DB::table('pages')->where('slug', $page['slug'])->exists()) {
                DB::table('pages')->insert(array_merge($page, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
