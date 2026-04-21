<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MouAgreement;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MouController extends Controller
{
    private const STATUSES = [
        'active'     => ['lo' => 'ຍັງໃຊ້ງານ', 'class' => 'bg-green-100 text-green-700',  'icon' => 'fa-circle'],
        'pending'    => ['lo' => 'ລໍຖ້າ',      'class' => 'bg-amber-100 text-amber-700',  'icon' => 'fa-clock'],
        'renewed'    => ['lo' => 'ຕໍ່ອາຍຸ',    'class' => 'bg-blue-100 text-blue-700',    'icon' => 'fa-redo'],
        'expired'    => ['lo' => 'ໝົດອາຍຸ',   'class' => 'bg-gray-100 text-gray-500',    'icon' => 'fa-calendar-times'],
        'terminated' => ['lo' => 'ຍົກເລີກ',    'class' => 'bg-red-100 text-red-600',      'icon' => 'fa-ban'],
    ];

    public function index(Request $request)
    {
        $query = MouAgreement::with('partnerOrganization')->latest('signed_date');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%{$s}%")
                  ->orWhere('title_en', 'like', "%{$s}%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->partner_id) {
            $query->where('partner_org_id', $request->partner_id);
        }

        $mous     = $query->paginate(15)->withQueryString();
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);

        return view('admin.mou.index', [
            'mous'     => $mous,
            'partners' => $partners,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.mou.create', [
            'partners' => $partners,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'partner_org_id' => 'required|exists:partner_organizations,id',
            'signed_date'    => 'required|date',
            'expiry_date'    => 'nullable|date|after:signed_date',
            'status'         => 'required|in:active,expired,pending,renewed,terminated',
            'doc_file'       => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'doc_url'        => 'nullable|url|max:500',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'signers_lo'     => 'nullable|string',
            'signers_en'     => 'nullable|string',
            'signers_zh'     => 'nullable|string',
            'scope_lo'       => 'nullable|string',
            'scope_en'       => 'nullable|string',
            'scope_zh'       => 'nullable|string',
        ]);

        $documentUrl = null;
        if ($request->hasFile('doc_file')) {
            $path = $request->file('doc_file')->store('mou-documents', 'public');
            $documentUrl = '/storage/' . $path;
        } elseif ($request->filled('doc_url')) {
            $documentUrl = $request->doc_url;
        }

        unset($validated['doc_file'], $validated['doc_url']);
        $validated['document_url'] = $documentUrl;

        MouAgreement::create($validated);

        return redirect()->route('admin.mou.index')
                         ->with('success', 'ບັນທຶກ MOU ສຳເລັດ');
    }

    public function show(MouAgreement $mou)
    {
        $mou->load('partnerOrganization');
        return view('admin.mou.show', [
            'mou'      => $mou,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(MouAgreement $mou)
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.mou.edit', [
            'mou'      => $mou,
            'partners' => $partners,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, MouAgreement $mou)
    {
        $validated = $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'partner_org_id' => 'required|exists:partner_organizations,id',
            'signed_date'    => 'required|date',
            'expiry_date'    => 'nullable|date|after:signed_date',
            'status'         => 'required|in:active,expired,pending,renewed,terminated',
            'doc_file'       => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'doc_url'        => 'nullable|url|max:500',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'signers_lo'     => 'nullable|string',
            'signers_en'     => 'nullable|string',
            'signers_zh'     => 'nullable|string',
            'scope_lo'       => 'nullable|string',
            'scope_en'       => 'nullable|string',
            'scope_zh'       => 'nullable|string',
        ]);

        $documentUrl = $mou->document_url;

        if ($request->hasFile('doc_file')) {
            $this->deleteLocalFile($mou->document_url);
            $path = $request->file('doc_file')->store('mou-documents', 'public');
            $documentUrl = '/storage/' . $path;
        } elseif ($request->filled('doc_url')) {
            $documentUrl = $request->doc_url;
        } elseif ($request->boolean('clear_document')) {
            $this->deleteLocalFile($mou->document_url);
            $documentUrl = null;
        }

        unset($validated['doc_file'], $validated['doc_url']);
        $validated['document_url'] = $documentUrl;

        $mou->update($validated);

        return redirect()->route('admin.mou.show', $mou)
                         ->with('success', 'ອັບເດດ MOU ສຳເລັດ');
    }

    public function destroy(MouAgreement $mou)
    {
        $this->deleteLocalFile($mou->document_url);
        $mou->delete();

        return redirect()->route('admin.mou.index')
                         ->with('success', 'ລຶບ MOU ສຳເລັດ');
    }

    private function deleteLocalFile(?string $url): void
    {
        if ($url && str_starts_with($url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
