<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('members')->orderBy('sort_order')->orderBy('name_lo')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $department = new Department();
        return view('admin.departments.form', compact('department'));
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
        return view('admin.departments.form', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $this->validated($request);
        $department->update($data);
        return redirect()->route('admin.departments.index')->with('success', 'ແກ້ໄຂພະແນກສຳເລັດ');
    }

    public function destroy(Department $department)
    {
        if ($department->members()->exists()) {
            return back()->with('error', 'ບໍ່ສາມາດລົບໄດ້: ພະແນກນີ້ຍັງມີສະມາຊິກຢູ່ ' . $department->members()->count() . ' ຄົນ');
        }
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'ລົບພະແນກສຳເລັດ');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
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
