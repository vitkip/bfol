<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Department;

class StructureController extends Controller
{
    public function index()
    {
        $rootDepts = $this->loadRootDepts();
        $president = $this->findPresident($rootDepts);

        $deptGroups = $rootDepts
            ->map(fn($d) => $this->buildDeptGroup($d, $president))
            ->filter(fn($g) => $g['head'] !== null || $g['subGroups']->isNotEmpty())
            ->values();

        $totalMembers = $this->collectAllMembers($rootDepts)->count();

        return view('front.structure.index', compact('president', 'deptGroups', 'totalMembers'));
    }

    public function d3()
    {
        $L  = app()->getLocale();
        $tf = fn($m, $f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

        $rootDepts = $this->loadRootDepts();
        $president = $this->findPresident($rootDepts);

        $buildD3Dept = null;
        $buildD3Dept = function (Department $dept, int $rootIdx, int $level) use ($tf, $president, &$buildD3Dept): ?array {
            $members = $dept->members->reject(fn($m) => $m->id === $president?->id)->values();
            $head    = $members->first();
            if (!$head) return null;

            $deptName = $tf($dept, 'name');

            $subNodes = $dept->children
                ->map(fn($child) => $buildD3Dept($child, $rootIdx, $level + 1))
                ->filter()->values()->all();

            $memberNodes = $members->skip(1)->map(fn($m) => [
                'id'       => $m->id,
                'name'     => $tf($m, 'name'),
                'title'    => $tf($m, 'title'),
                'position' => $tf($m, 'position'),
                'photo'    => $m->photo_url ?? '',
                'gender'   => $m->gender ?? 'male',
                'dept'     => $deptName,
                'deptIdx'  => $rootIdx,
                'level'    => $level + 1,
                'children' => [],
            ])->values()->all();

            return [
                'id'       => $head->id,
                'name'     => $tf($head, 'name'),
                'title'    => $tf($head, 'title'),
                'position' => $tf($head, 'position'),
                'photo'    => $head->photo_url ?? '',
                'gender'   => $head->gender ?? 'male',
                'dept'     => $deptName,
                'deptIdx'  => $rootIdx,
                'level'    => $level,
                'children' => array_merge($subNodes, $memberNodes),
            ];
        };

        $deptChildren = $rootDepts
            ->values()
            ->map(fn($dept, $idx) => $buildD3Dept($dept, $idx, 2))
            ->filter()->values()->all();

        $treeData = $president ? [
            'id'       => $president->id,
            'name'     => $tf($president, 'name'),
            'title'    => $tf($president, 'title'),
            'position' => $tf($president, 'position'),
            'photo'    => $president->photo_url ?? '',
            'gender'   => $president->gender ?? 'monk',
            'dept'     => '',
            'deptIdx'  => -1,
            'level'    => 1,
            'children' => $deptChildren,
        ] : [];

        return view('front.structure.d3', compact('treeData'));
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function loadRootDepts()
    {
        $memberFilter = fn($q) => $q->where('is_active', true)->orderBy('sort_order');

        return Department::where('is_active', true)
            ->whereNull('parent_id')               // ROOT only — sub-depts come via children relation
            ->with([
                'members'                         => $memberFilter,
                'children'                        => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'children.members'                => $memberFilter,
                'children.children'               => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'children.children.members'       => $memberFilter,
            ])
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get();
    }

    /**
     * Collect all members from root depts AND all nested sub-depts.
     */
    private function collectAllMembers($depts, $seen = [])
    {
        $all = collect();
        foreach ($depts as $dept) {
            foreach ($dept->members as $m) {
                if (!in_array($m->id, $seen)) { $all->push($m); $seen[] = $m->id; }
            }
            if ($dept->children->isNotEmpty()) {
                $all = $all->merge($this->collectAllMembers($dept->children, $seen));
            }
        }
        return $all;
    }

    /** President = globally lowest sort_order across ALL departments (including sub-depts). */
    private function findPresident($rootDepts)
    {
        return $this->collectAllMembers($rootDepts)->sortBy('sort_order')->first();
    }

    /**
     * Recursively build a dept group for the Blade view.
     * Returns:
     *   dept      — Department model
     *   head      — first member (excluding president) = dept head
     *   members   — remaining direct members
     *   subGroups — recursive groups for each child Department
     */
    private function buildDeptGroup(Department $dept, $president): array
    {
        $members = $dept->members
            ->reject(fn($m) => $m->id === $president?->id)
            ->values();

        $subGroups = $dept->children
            ->map(fn($child) => $this->buildDeptGroup($child, $president))
            ->filter(fn($g) => $g['head'] !== null || $g['subGroups']->isNotEmpty())
            ->values();

        return [
            'dept'      => $dept,
            'head'      => $members->first(),
            'members'   => $members->skip(1)->values(),
            'subGroups' => $subGroups,
        ];
    }
}
