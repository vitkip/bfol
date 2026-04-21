<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name_lo',    'value' => 'ກັມມາທິການການຕ່າງປະເທດສູນກາງອພສ', 'type' => 'text',    'group' => 'general', 'label_lo' => 'ຊື່ເວັບ (ລາວ)',      'label_en' => 'Site Name (Lao)',    'label_zh' => '网站名称（老挝语）'],
            ['key' => 'site_name_en',    'value' => 'BFOL Foreign Affairs Committee',               'type' => 'text',    'group' => 'general', 'label_lo' => 'ຊື່ເວັບ (EN)',       'label_en' => 'Site Name (EN)',     'label_zh' => '网站名称（英文）'],
            ['key' => 'site_name_zh',    'value' => '老挝佛教联合会中央对外委员会',                      'type' => 'text',    'group' => 'general', 'label_lo' => 'ຊື່ເວັບ (ຈີນ)',      'label_en' => 'Site Name (ZH)',     'label_zh' => '网站名称（中文）'],
            ['key' => 'site_email',      'value' => 'bfol.foreign@gmail.com',                       'type' => 'text',    'group' => 'contact', 'label_lo' => 'ອີເມວ',              'label_en' => 'Email',             'label_zh' => '电子邮件'],
            ['key' => 'site_phone',      'value' => '021-000-000',                                  'type' => 'text',    'group' => 'contact', 'label_lo' => 'ເບີໂທ',              'label_en' => 'Phone',             'label_zh' => '电话'],
            ['key' => 'site_address_lo', 'value' => 'ວຽງຈັນ, ສ.ປ.ປ. ລາວ',                        'type' => 'text',    'group' => 'contact', 'label_lo' => 'ທີ່ຢູ່',              'label_en' => 'Address',           'label_zh' => '地址'],
            ['key' => 'site_address_zh', 'value' => '万象，老挝人民民主共和国',                          'type' => 'text',    'group' => 'contact', 'label_lo' => 'ທີ່ຢູ່ (ຈີນ)',        'label_en' => 'Address (ZH)',       'label_zh' => '地址（中文）'],
            ['key' => 'site_facebook',   'value' => 'https://www.facebook.com/DhammaOnLen',         'type' => 'text',    'group' => 'social',  'label_lo' => 'Facebook',            'label_en' => 'Facebook',          'label_zh' => 'Facebook'],
            ['key' => 'site_youtube',    'value' => '',                                             'type' => 'text',    'group' => 'social',  'label_lo' => 'YouTube',             'label_en' => 'YouTube',           'label_zh' => 'YouTube'],
            ['key' => 'site_line',       'value' => '',                                             'type' => 'text',    'group' => 'social',  'label_lo' => 'Line',                'label_en' => 'Line',              'label_zh' => 'Line'],
            ['key' => 'site_wechat',     'value' => '',                                             'type' => 'text',    'group' => 'social',  'label_lo' => 'WeChat',              'label_en' => 'WeChat',            'label_zh' => '微信'],
            ['key' => 'news_per_page',   'value' => '10',                                           'type' => 'number',  'group' => 'display', 'label_lo' => 'ຂ່າວຕໍ່ໜ້າ',        'label_en' => 'News per page',     'label_zh' => '每页新闻数'],
            ['key' => 'events_per_page', 'value' => '9',                                            'type' => 'number',  'group' => 'display', 'label_lo' => 'ກິດຈະກໍາຕໍ່ໜ້າ',  'label_en' => 'Events per page',   'label_zh' => '每页活动数'],
            ['key' => 'maintenance_mode','value' => '0',                                            'type' => 'boolean', 'group' => 'system',  'label_lo' => 'ໂໝດບໍລຸງຮັກສາ',  'label_en' => 'Maintenance Mode',  'label_zh' => '维护模式'],
            ['key' => 'logo_url',        'value' => '/assets/images/logo.png',                     'type' => 'image',   'group' => 'general', 'label_lo' => 'ໂລໂກ',               'label_en' => 'Logo',              'label_zh' => '徽标'],
            ['key' => 'favicon_url',     'value' => '/assets/images/favicon.ico',                  'type' => 'image',   'group' => 'general', 'label_lo' => 'Favicon',             'label_en' => 'Favicon',           'label_zh' => '网站图标'],
            ['key' => 'office_hours_lo', 'value' => 'ຈ-ສ: 8:00 - 17:00',                          'type' => 'text',    'group' => 'contact', 'label_lo' => 'ເວລາທໍາການ',       'label_en' => 'Office Hours',      'label_zh' => '办公时间'],
            ['key' => 'default_language','value' => 'lo',                                           'type' => 'text',    'group' => 'system',  'label_lo' => 'ພາສາຕັ້ງຕົ້ນ',     'label_en' => 'Default Language',  'label_zh' => '默认语言'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
