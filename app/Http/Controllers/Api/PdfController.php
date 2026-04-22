<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class PdfController extends Controller
{
    private function mpdf(string $title): Mpdf
    {
        $mpdf = new Mpdf([
            'mode'         => 'utf-8',
            'format'       => 'A4',
            'margin_left'  => 15,
            'margin_right' => 15,
            'margin_top'   => 20,
            'margin_bottom'=> 20,
            'default_font' => 'dejavusans',
        ]);

        $mpdf->SetTitle($title);
        $mpdf->SetCreator('BFOL Admin');

        return $mpdf;
    }

    private function header(string $title, string $subtitle = ''): string
    {
        return <<<HTML
        <style>
            body { font-family: dejavusans, sans-serif; font-size: 11pt; color: #1a1a2e; }
            h1 { font-size: 16pt; color: #1a56db; margin-bottom: 4px; }
            h2 { font-size: 12pt; color: #6b7280; font-weight: normal; margin-top: 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; }
            th { background: #1a56db; color: #fff; padding: 8px 10px; font-size: 10pt; text-align: left; }
            td { padding: 7px 10px; font-size: 10pt; border-bottom: 1px solid #e5e7eb; }
            tr:nth-child(even) td { background: #f9fafb; }
            .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9pt; }
            .badge-green  { background: #dcfce7; color: #166534; }
            .badge-yellow { background: #fef9c3; color: #713f12; }
            .badge-red    { background: #fee2e2; color: #991b1b; }
            .badge-gray   { background: #f3f4f6; color: #374151; }
            .footer { margin-top: 20px; font-size: 9pt; color: #9ca3af; text-align: center; }
        </style>
        <h1>{$title}</h1>
        <h2>{$subtitle}</h2>
        HTML;
    }

    public function news(Request $request)
    {
        $items = News::with(['category', 'author'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->limit(200)->get();

        $html = $this->header('ລາຍງານຂ່າວສານ', 'ສ້າງໃນວັນທີ ' . now()->format('d/m/Y'));
        $html .= '<table><tr>';
        $html .= '<th>#</th><th>ຫົວຂໍ້</th><th>ໝວດໝູ່</th><th>ສະຖານະ</th><th>ຜູ້ຂຽນ</th><th>ວັນທີ</th>';
        $html .= '</tr>';

        foreach ($items as $i => $n) {
            $badge = match ($n->status) {
                'published' => 'badge-green',
                'draft'     => 'badge-yellow',
                default     => 'badge-gray',
            };
            $status_label = match ($n->status) {
                'published' => 'ເຜີຍແຜ່',
                'draft'     => 'ຮ່າງ',
                'archived'  => 'ເກັບ',
                default     => $n->status,
            };
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . ($n->title_lo ?: $n->title_en) . '</td>';
            $html .= '<td>' . ($n->category?->name_lo ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $badge . '">' . $status_label . '</span></td>';
            $html .= '<td>' . ($n->author?->full_name_lo ?: '-') . '</td>';
            $html .= '<td>' . ($n->published_at?->format('d/m/Y') ?? $n->created_at?->format('d/m/Y')) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table><div class="footer">ທັງໝົດ ' . $items->count() . ' ລາຍການ &bull; BFOL © ' . date('Y') . '</div>';

        $mpdf = $this->mpdf('ລາຍງານຂ່າວສານ');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('news_report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="news_report.pdf"');
    }

    public function events(Request $request)
    {
        $items = Event::with('category')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->limit(200)->get();

        $html = $this->header('ລາຍງານກິດຈະກຳ', 'ສ້າງໃນວັນທີ ' . now()->format('d/m/Y'));
        $html .= '<table><tr>';
        $html .= '<th>#</th><th>ຫົວຂໍ້</th><th>ສະຖານທີ</th><th>ວັນທີ</th><th>ສະຖານະ</th>';
        $html .= '</tr>';

        foreach ($items as $i => $e) {
            $badge = match ($e->status) {
                'upcoming'  => 'badge-yellow',
                'ongoing'   => 'badge-green',
                'completed' => 'badge-gray',
                default     => 'badge-red',
            };
            $status_label = match ($e->status) {
                'upcoming'  => 'ກຳລັງຈະມາ',
                'ongoing'   => 'ດຳເນີນຢູ່',
                'completed' => 'ສຳເລັດ',
                'cancelled' => 'ຍົກເລີກ',
                default     => $e->status,
            };
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . ($e->title_lo ?: $e->title_en) . '</td>';
            $html .= '<td>' . ($e->location_lo ?: '-') . '</td>';
            $html .= '<td>' . ($e->start_date?->format('d/m/Y') ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $badge . '">' . $status_label . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</table><div class="footer">ທັງໝົດ ' . $items->count() . ' ລາຍການ &bull; BFOL © ' . date('Y') . '</div>';

        $mpdf = $this->mpdf('ລາຍງານກິດຈະກຳ');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('events_report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="events_report.pdf"');
    }

    public function partners()
    {
        $items = PartnerOrganization::orderBy('name_lo')->limit(200)->get();

        $html = $this->header('ລາຍງານຄູ່ຮ່ວມມື', 'ສ້າງໃນວັນທີ ' . now()->format('d/m/Y'));
        $html .= '<table><tr>';
        $html .= '<th>#</th><th>ຊື່</th><th>ຕົວຫຍໍ້</th><th>ປະເທດ</th><th>ປະເພດ</th><th>ສະຖານະ</th>';
        $html .= '</tr>';

        foreach ($items as $i => $p) {
            $badge = $p->status === 'active' ? 'badge-green' : 'badge-gray';
            $status_lo = $p->status === 'active' ? 'ເຄື່ອນໄຫວ' : ($p->status === 'inactive' ? 'ບໍ່ເຄື່ອນໄຫວ' : 'ລໍຖ້າ');
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . ($p->name_lo ?: $p->name_en) . '</td>';
            $html .= '<td>' . ($p->acronym ?? '-') . '</td>';
            $html .= '<td>' . ($p->country_name_lo ?: $p->country_name_en ?? '-') . '</td>';
            $html .= '<td>' . ($p->type ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $badge . '">' . $status_lo . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</table><div class="footer">ທັງໝົດ ' . $items->count() . ' ລາຍການ &bull; BFOL © ' . date('Y') . '</div>';

        $mpdf = $this->mpdf('ລາຍງານຄູ່ຮ່ວມມື');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('partners_report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="partners_report.pdf"');
    }

    public function mou()
    {
        $items = MouAgreement::with('partnerOrganization')->latest()->limit(200)->get();

        $html = $this->header('ລາຍງານ MOU', 'ສ້າງໃນວັນທີ ' . now()->format('d/m/Y'));
        $html .= '<table><tr>';
        $html .= '<th>#</th><th>ຫົວຂໍ້</th><th>ຄູ່ຮ່ວມ</th><th>ວັນທີເຊັນ</th><th>ໝົດອາຍຸ</th><th>ສະຖານະ</th>';
        $html .= '</tr>';

        foreach ($items as $i => $m) {
            $badge = match ($m->status) {
                'active'     => 'badge-green',
                'expired'    => 'badge-red',
                'pending'    => 'badge-yellow',
                default      => 'badge-gray',
            };
            $status_lo = match ($m->status) {
                'active'     => 'ມີຜົນ',
                'expired'    => 'ໝົດອາຍຸ',
                'pending'    => 'ລໍຖ້າ',
                'terminated' => 'ຍົກເລີກ',
                default      => $m->status,
            };
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . ($m->title_lo ?: $m->title_en) . '</td>';
            $html .= '<td>' . ($m->partnerOrganization?->name_lo ?: '-') . '</td>';
            $html .= '<td>' . ($m->signed_date?->format('d/m/Y') ?? '-') . '</td>';
            $html .= '<td>' . ($m->expiry_date?->format('d/m/Y') ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $badge . '">' . $status_lo . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</table><div class="footer">ທັງໝົດ ' . $items->count() . ' ລາຍການ &bull; BFOL © ' . date('Y') . '</div>';

        $mpdf = $this->mpdf('ລາຍງານ MOU');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('mou_report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="mou_report.pdf"');
    }

    public function committee()
    {
        $items = CommitteeMember::orderBy('sort_order')->limit(100)->get();

        $html = $this->header('ລາຍຊື່ຄະນະກຳມະການ', 'ສ້າງໃນວັນທີ ' . now()->format('d/m/Y'));
        $html .= '<table><tr>';
        $html .= '<th>#</th><th>ຊື່</th><th>ຕຳແໜ່ງ</th><th>ພະແນກ</th><th>ອີເມວ</th><th>ສະຖານະ</th>';
        $html .= '</tr>';

        foreach ($items as $i => $m) {
            $badge = $m->is_active ? 'badge-green' : 'badge-gray';
            $status_lo = $m->is_active ? 'ເຄື່ອນໄຫວ' : 'ບໍ່ເຄື່ອນໄຫວ';
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . ($m->name_lo ?: $m->name_en) . '</td>';
            $html .= '<td>' . ($m->position_lo ?: '-') . '</td>';
            $html .= '<td>' . ($m->department ?? '-') . '</td>';
            $html .= '<td>' . ($m->email ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $badge . '">' . $status_lo . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</table><div class="footer">ທັງໝົດ ' . $items->count() . ' ລາຍການ &bull; BFOL © ' . date('Y') . '</div>';

        $mpdf = $this->mpdf('ລາຍຊື່ຄະນະກຳມະການ');
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('committee_report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="committee_report.pdf"');
    }
}
