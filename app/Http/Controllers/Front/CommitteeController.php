<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use App\Models\Department;

class CommitteeController extends Controller
{
    public function index()
    {
        $departments = Department::active()
            ->with(['members' => fn($q) => $q->active()])
            ->get();

        $ungrouped = CommitteeMember::active()
            ->whereNull('department_id')
            ->get();

        return view('front.committee.index', compact('departments', 'ungrouped'));
    }
}
