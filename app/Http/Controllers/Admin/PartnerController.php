<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    private const TYPES = [
        'buddhist_org' => ['lo' => 'ອົງກອນພຸດທະ',  'icon' => 'fa-dharmachakra',  'class' => 'bg-amber-100 text-amber-700'],
        'government'   => ['lo' => 'ລັດຖະບານ',      'icon' => 'fa-landmark',       'class' => 'bg-blue-100 text-blue-700'],
        'ngo'          => ['lo' => 'NGO',             'icon' => 'fa-hands-helping',  'class' => 'bg-green-100 text-green-700'],
        'academic'     => ['lo' => 'ການສຶກສາ',      'icon' => 'fa-graduation-cap', 'class' => 'bg-purple-100 text-purple-700'],
        'media'        => ['lo' => 'ສື່ມວນຊົນ',     'icon' => 'fa-broadcast-tower','class' => 'bg-pink-100 text-pink-700'],
        'un_agency'    => ['lo' => 'ອົງການ UN',      'icon' => 'fa-globe-asia',     'class' => 'bg-sky-100 text-sky-700'],
        'other'        => ['lo' => 'ອື່ນໆ',           'icon' => 'fa-ellipsis-h',    'class' => 'bg-gray-100 text-gray-500'],
    ];

    private const STATUSES = [
        'active'   => ['lo' => 'ໃຊ້ງານ', 'class' => 'bg-green-100 text-green-700'],
        'inactive' => ['lo' => 'ປິດໃຊ້', 'class' => 'bg-gray-100 text-gray-500'],
        'pending'  => ['lo' => 'ລໍຖ້າ',  'class' => 'bg-amber-100 text-amber-700'],
    ];

    public function index(Request $request)
    {
        $query = PartnerOrganization::withCount('mouAgreements')->orderBy('sort_order')->orderBy('name_lo');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('name_lo', 'like', "%{$s}%")
                  ->orWhere('name_en', 'like', "%{$s}%")
                  ->orWhere('acronym', 'like', "%{$s}%");
            });
        }
        if ($request->type)   { $query->where('type',   $request->type); }
        if ($request->status) { $query->where('status', $request->status); }

        $partners = $query->paginate(20)->withQueryString();

        return view('admin.partners.index', [
            'partners' => $partners,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        return view('admin.partners.create', [
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_lo'          => 'required|string|max:200',
            'name_en'          => 'nullable|string|max:200',
            'name_zh'          => 'nullable|string|max:200',
            'acronym'          => 'nullable|string|max:30',
            'country_code'     => 'required|string|size:2',
            'country_name_lo'  => 'required|string|max:100',
            'country_name_en'  => 'nullable|string|max:100',
            'country_name_zh'  => 'nullable|string|max:100',
            'logo_file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp|max:5120',
            'logo_url'         => 'nullable|string|max:500',
            'website_url'      => 'nullable|url|max:500',
            'description_lo'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'description_zh'   => 'nullable|string',
            'contact_person'   => 'nullable|string|max:200',
            'contact_email'    => 'nullable|email|max:120',
            'contact_phone'    => 'nullable|string|max:50',
            'type'             => 'required|in:buddhist_org,government,ngo,academic,media,un_agency,other',
            'partnership_since'=> 'nullable|integer|min:1900|max:' . date('Y'),
            'status'           => 'required|in:active,inactive,pending',
            'sort_order'       => 'nullable|integer',
        ]);

        $validated['logo_url'] = $this->handleLogo($request, null, $validated['logo_url'] ?? null);
        $validated['country_code'] = strtoupper($validated['country_code']);
        unset($validated['logo_file']);

        PartnerOrganization::create($validated);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'ເພີ່ມອົງກອນຄູ່ຮ່ວມສຳເລັດ');
    }

    public function show(PartnerOrganization $partner)
    {
        $partner->loadCount('mouAgreements');
        $partner->load('mouAgreements');
        return view('admin.partners.show', [
            'partner'  => $partner,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(PartnerOrganization $partner)
    {
        return view('admin.partners.edit', [
            'partner'  => $partner,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, PartnerOrganization $partner)
    {
        $validated = $request->validate([
            'name_lo'          => 'required|string|max:200',
            'name_en'          => 'nullable|string|max:200',
            'name_zh'          => 'nullable|string|max:200',
            'acronym'          => 'nullable|string|max:30',
            'country_code'     => 'required|string|size:2',
            'country_name_lo'  => 'required|string|max:100',
            'country_name_en'  => 'nullable|string|max:100',
            'country_name_zh'  => 'nullable|string|max:100',
            'logo_file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp|max:5120',
            'logo_url'         => 'nullable|string|max:500',
            'website_url'      => 'nullable|url|max:500',
            'description_lo'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'description_zh'   => 'nullable|string',
            'contact_person'   => 'nullable|string|max:200',
            'contact_email'    => 'nullable|email|max:120',
            'contact_phone'    => 'nullable|string|max:50',
            'type'             => 'required|in:buddhist_org,government,ngo,academic,media,un_agency,other',
            'partnership_since'=> 'nullable|integer|min:1900|max:' . date('Y'),
            'status'           => 'required|in:active,inactive,pending',
            'sort_order'       => 'nullable|integer',
        ]);

        $validated['logo_url'] = $this->handleLogo($request, $partner, $validated['logo_url'] ?? null);
        $validated['country_code'] = strtoupper($validated['country_code']);
        unset($validated['logo_file']);

        $partner->update($validated);

        return redirect()->route('admin.partners.show', $partner)
                         ->with('success', 'ອັບເດດອົງກອນສຳເລັດ');
    }

    public function destroy(PartnerOrganization $partner)
    {
        if ($partner->mouAgreements()->exists()) {
            return redirect()->route('admin.partners.index')
                             ->with('error', 'ບໍ່ສາມາດລຶບໄດ້: ອົງກອນນີ້ຍັງມີ MOU ທີ່ຜູກພັນຢູ່');
        }

        $this->deleteLocalLogo($partner->logo_url);
        $partner->delete();

        return redirect()->route('admin.partners.index')
                         ->with('success', 'ລຶບອົງກອນສຳເລັດ');
    }

    private function handleLogo(Request $request, ?PartnerOrganization $existing, ?string $urlInput): ?string
    {
        if ($request->hasFile('logo_file')) {
            $this->deleteLocalLogo($existing?->logo_url);
            $path = $request->file('logo_file')->store('partner-logos', 'public');
            return '/storage/' . $path;
        }

        if ($urlInput) {
            return $urlInput;
        }

        return $existing?->logo_url;
    }

    private function deleteLocalLogo(?string $url): void
    {
        if ($url && str_starts_with($url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
