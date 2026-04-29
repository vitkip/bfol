<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // Root depts with nested children (up to 3 levels) + member counts
        $roots = Department::with([
                'children.children.members',
                'children.children' => fn($q) => $q->withCount('members'),
                'children'          => fn($q) => $q->withCount('members'),
            ])
            ->withCount('members')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get();

        return view('admin.departments.index', compact('roots'));
    }

    public function create()
    {
        $department = new Department();
        $parents    = $this->parentOptions();
        return view('admin.departments.form', compact('department', 'parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Department::create($data);
        return redirect()->route('admin.departments.index')->with('success', 'ເພີ່ມພະແນກສຳເລັດ');
    }

    public function show($id)
    {
        return redirect()->route('admin.departments.edit', $id);
    }

    public function edit(Department $department)
    {
        $department->load('children');
        $excludeIds = array_merge([$department->id], $department->getAllDescendantIds());
        $parents    = $this->parentOptions($excludeIds);
        return view('admin.departments.form', compact('department', 'parents'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $this->validated($request);

        // Prevent circular parent
        if (!empty($data['parent_id'])) {
            $department->load('children');
            $forbidden = array_merge([$department->id], $department->getAllDescendantIds());
            if (in_array((int) $data['parent_id'], $forbidden)) {
                return back()->withInput()
                    ->withErrors(['parent_id' => 'ບໍ່ສາມາດເລືອກຕົວເອງ ຫຼື sub-ພະແນກ ເປັນ parent']);
            }
        }

        $department->update($data);
        return redirect()->route('admin.departments.index')->with('success', 'ແກ້ໄຂພະແນກສຳເລັດ');
    }

    public function destroy(Department $department)
    {
        if ($department->children()->exists()) {
            return back()->with('error',
                'ບໍ່ສາມາດລົບໄດ້: ພະແນກນີ້ຍັງມີ sub-ພະແນກ '.$department->children()->count().' ລາຍ — ລົບລູກກ່ອນ');
        }
        if ($department->members()->exists()) {
            return back()->with('error',
                'ບໍ່ສາມາດລົບໄດ້: ພະແນກນີ້ຍັງມີສະມາຊິກ '.$department->members()->count().' ຄົນ');
        }
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'ລົບພະແນກສຳເລັດ');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function parentOptions(array $excludeIds = []): \Illuminate\Support\Collection
    {
        return Department::with('parent')
            ->when($excludeIds, fn($q) => $q->whereNotIn('id', $excludeIds))
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get()
            ->map(fn($d) => [
                'id'    => $d->id,
                'label' => $d->breadcrumbName(),
                'depth' => $d->parent_id ? 1 : 0,
            ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'parent_id'       => ['nullable', 'exists:departments,id'],
            'name_lo'         => ['required', 'string', 'max:200'],
            'name_en'         => ['nullable', 'string', 'max:200'],
            'name_zh'         => ['nullable', 'string', 'max:200'],
            'description_lo'  => ['nullable', 'string'],
            'description_en'  => ['nullable', 'string'],
            'description_zh'  => ['nullable', 'string'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'is_active'       => ['nullable', 'boolean'],
        ]);
    }
}
