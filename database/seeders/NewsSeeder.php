<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $news = [];
        for ($i = 1; $i <= 10; $i++) {
            $news[] = [
                'title_lo' => "ຂ່າວຕົວຢ່າງ $i",
                'title_en' => "Sample News $i",
                'title_zh' => "示例新闻 {$i}",
                'slug' => Str::slug("sample-news-$i-" . uniqid()),
                'excerpt_lo' => "ນີ້ແມ່ນຂ່າວຕົວຢ່າງ $i.",
                'excerpt_en' => "This is sample news $i.",
                'excerpt_zh' => "这是一条示例新闻 {$i}。",
                'content_lo' => "ເນື້ອຫາຂອງຂ່າວຕົວຢ່າງ $i.",
                'content_en' => "Content of sample news $i.",
                'content_zh' => "示例新闻 {$i} 的内容。",
                'thumbnail' => null,
                'category_id' => 1, // Change as needed
                'author_id' => 1, // Change as needed
                'status' => 'published',
                'is_featured' => $i % 2 === 0,
                'is_urgent' => $i % 3 === 0,
                'view_count' => rand(0, 100),
                'published_at' => $now->copy()->subDays($i),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('news')->insert($news);
    }
}
