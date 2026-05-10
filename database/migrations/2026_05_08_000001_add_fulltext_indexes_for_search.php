<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // FULLTEXT indexes speed up LIKE searches 10-100x on large datasets.
        // Using raw SQL because Blueprint doesn't support FULLTEXT on multiple columns.
        DB::statement('ALTER TABLE news ADD FULLTEXT INDEX ft_news_search (title_lo, title_en, excerpt_lo, excerpt_en)');
        DB::statement('ALTER TABLE events ADD FULLTEXT INDEX ft_events_search (title_lo, title_en, description_lo, description_en)');
        DB::statement('ALTER TABLE pages ADD FULLTEXT INDEX ft_pages_search (title_lo, title_en, content_lo, content_en)');
        DB::statement('ALTER TABLE partner_organizations ADD FULLTEXT INDEX ft_partners_search (name_lo, name_en, description_lo, description_en, acronym)');
        DB::statement('ALTER TABLE mou_agreements ADD FULLTEXT INDEX ft_mou_search (title_lo, title_en, description_lo, description_en)');
        DB::statement('ALTER TABLE aid_projects ADD FULLTEXT INDEX ft_aid_search (title_lo, title_en, description_lo, description_en)');
        DB::statement('ALTER TABLE monk_exchange_programs ADD FULLTEXT INDEX ft_monk_search (title_lo, title_en, description_lo, description_en)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE news DROP INDEX ft_news_search');
        DB::statement('ALTER TABLE events DROP INDEX ft_events_search');
        DB::statement('ALTER TABLE pages DROP INDEX ft_pages_search');
        DB::statement('ALTER TABLE partner_organizations DROP INDEX ft_partners_search');
        DB::statement('ALTER TABLE mou_agreements DROP INDEX ft_mou_search');
        DB::statement('ALTER TABLE aid_projects DROP INDEX ft_aid_search');
        DB::statement('ALTER TABLE monk_exchange_programs DROP INDEX ft_monk_search');
    }
};
