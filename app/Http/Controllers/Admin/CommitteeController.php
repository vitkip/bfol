<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $query = CommitteeMember::with('department')->orderBy('sort_order')->orderBy('id');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name_lo', 'like', "%$s%")
                  ->orWhere('first_name_lo', 'like', "%$s%")
                  ->orWhere('last_name_lo', 'like', "%$s%")
                  ->orWhere('position_lo', 'like', "%$s%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $members     = $query->paginate(20)->withQueryString();
        $departments = Department::active()->get();
        $counts      = [
            'all'      => CommitteeMember::count(),
            'active'   => CommitteeMember::where('is_active', true)->count(),
            'inactive' => CommitteeMember::where('is_active', false)->count(),
        ];

        return view('admin.committee.index', compact('members', 'departments', 'counts'));
    }

    public function create()
    {
        $member      = new CommitteeMember();
        $departments = Department::active()->get();
        return view('admin.committee.form', compact('member', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $data['photo_url'] = '/storage/' . $request->file('photo')->store('committee', 'public');
        }

        CommitteeMember::create($data);

        return redirect()->route('admin.committee.index')
                         ->with('success', 'ເພີ່ມສະມາຊິກສຳເລັດ');
    }

    public function show($id)
    {
        return redirect()->route('admin.committee.edit', $id);
    }

    public function edit(CommitteeMember $committee)
    {
        $departments = Department::active()->get();
        return view('admin.committee.form', ['member' => $committee, 'departments' => $departments]);
    }

    public function update(Request $request, CommitteeMember $committee)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            if ($committee->photo_url && str_starts_with($committee->photo_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $committee->photo_url));
            }
            $data['photo_url'] = '/storage/' . $request->file('photo')->store('committee', 'public');
        }

        $committee->update($data);

        return redirect()->route('admin.committee.index')
                         ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ');
    }

    public function destroy(CommitteeMember $committee)
    {
        if ($committee->photo_url && str_starts_with($committee->photo_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $committee->photo_url));
        }
        $committee->delete();

        return redirect()->route('admin.committee.index')
                         ->with('success', 'ລົບສະມາຊິກສຳເລັດ');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'department_id'        => ['nullable', 'exists:departments,id'],
            'gender'               => ['nullable', Rule::in(['monk', 'male', 'female'])],
            'first_name_lo'        => ['nullable', 'string', 'max:100'],
            'first_name_en'        => ['nullable', 'string', 'max:100'],
            'first_name_zh'        => ['nullable', 'string', 'max:100'],
            'last_name_lo'         => ['nullable', 'string', 'max:100'],
            'last_name_en'         => ['nullable', 'string', 'max:100'],
            'last_name_zh'         => ['nullable', 'string', 'max:100'],
            'name_lo'              => ['required', 'string', 'max:200'],
            'name_en'              => ['nullable', 'string', 'max:200'],
            'name_zh'              => ['nullable', 'string', 'max:200'],
            'title_lo'             => ['nullable', 'string', 'max:100'],
            'title_en'             => ['nullable', 'string', 'max:100'],
            'title_zh'             => ['nullable', 'string', 'max:100'],
            'position_lo'          => ['required', 'string', 'max:200'],
            'position_en'          => ['nullable', 'string', 'max:200'],
            'position_zh'          => ['nullable', 'string', 'max:200'],
            'bio_lo'               => ['nullable', 'string'],
            'bio_en'               => ['nullable', 'string'],
            'bio_zh'               => ['nullable', 'string'],
            'email'                => ['nullable', 'email', 'max:120'],
            'phone'                => ['nullable', 'string', 'max:50'],
            'facebook'             => ['nullable', 'string', 'max:300'],
            'date_of_birth'        => ['nullable', 'date'],
            'date_of_ordination'   => ['nullable', 'date'],
            'pansa'                => ['nullable', 'integer', 'min:0', 'max:200'],
            'education_lo'         => ['nullable', 'string', 'max:300'],
            'education_en'         => ['nullable', 'string', 'max:300'],
            'education_zh'         => ['nullable', 'string', 'max:300'],
            'birth_village_lo'     => ['nullable', 'string', 'max:200'],
            'birth_village_en'     => ['nullable', 'string', 'max:200'],
            'birth_village_zh'     => ['nullable', 'string', 'max:200'],
            'district_lo'          => ['nullable', 'string', 'max:100'],
            'district_en'          => ['nullable', 'string', 'max:100'],
            'district_zh'          => ['nullable', 'string', 'max:100'],
            'province_lo'          => ['nullable', 'string', 'max:100'],
            'province_en'          => ['nullable', 'string', 'max:100'],
            'province_zh'          => ['nullable', 'string', 'max:100'],
            'current_temple_lo'    => ['nullable', 'string', 'max:300'],
            'current_temple_en'    => ['nullable', 'string', 'max:300'],
            'current_temple_zh'    => ['nullable', 'string', 'max:300'],
            'term_start'           => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'term_end'             => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'sort_order'           => ['nullable', 'integer'],
            'is_active'            => ['nullable', 'boolean'],
            'photo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);
    }
}
