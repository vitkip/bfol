<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $query = CommitteeMember::orderBy('sort_order');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name_lo', 'like', "%$s%")
                  ->orWhere('name_en', 'like', "%$s%")
                  ->orWhere('position_lo', 'like', "%$s%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $members = $query->paginate($request->get('per_page', 20));

        return response()->json($members->through(fn($m) => $this->format($m)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_lo'      => ['required', 'string', 'max:200'],
            'name_en'      => ['nullable', 'string', 'max:200'],
            'name_zh'      => ['nullable', 'string', 'max:200'],
            'title_lo'     => ['nullable', 'string', 'max:200'],
            'title_en'     => ['nullable', 'string', 'max:200'],
            'position_lo'  => ['nullable', 'string', 'max:200'],
            'position_en'  => ['nullable', 'string', 'max:200'],
            'department'   => ['nullable', 'string', 'max:200'],
            'photo_url'    => ['nullable', 'url'],
            'bio_lo'       => ['nullable', 'string'],
            'bio_en'       => ['nullable', 'string'],
            'email'        => ['nullable', 'email'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'term_start'   => ['nullable', 'date'],
            'term_end'     => ['nullable', 'date'],
            'sort_order'   => ['nullable', 'integer'],
            'is_active'    => ['boolean'],
        ]);

        $member = CommitteeMember::create($data);

        return response()->json($this->format($member), 201);
    }

    public function show(CommitteeMember $committee)
    {
        return response()->json($this->format($committee));
    }

    public function update(Request $request, CommitteeMember $committee)
    {
        $data = $request->validate([
            'name_lo'      => ['required', 'string', 'max:200'],
            'name_en'      => ['nullable', 'string', 'max:200'],
            'title_lo'     => ['nullable', 'string', 'max:200'],
            'title_en'     => ['nullable', 'string', 'max:200'],
            'position_lo'  => ['nullable', 'string', 'max:200'],
            'position_en'  => ['nullable', 'string', 'max:200'],
            'department'   => ['nullable', 'string', 'max:200'],
            'photo_url'    => ['nullable', 'url'],
            'bio_lo'       => ['nullable', 'string'],
            'bio_en'       => ['nullable', 'string'],
            'email'        => ['nullable', 'email'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'term_start'   => ['nullable', 'date'],
            'term_end'     => ['nullable', 'date'],
            'sort_order'   => ['nullable', 'integer'],
            'is_active'    => ['boolean'],
        ]);

        $committee->update($data);

        return response()->json($this->format($committee));
    }

    public function destroy(CommitteeMember $committee)
    {
        $committee->delete();

        return response()->json(['message' => 'ລຶບຂໍ້ມູນສຳເລັດ']);
    }

    private function format(CommitteeMember $m): array
    {
        return [
            'id'          => $m->id,
            'name_lo'     => $m->name_lo,
            'name_en'     => $m->name_en,
            'title_lo'    => $m->title_lo,
            'title_en'    => $m->title_en,
            'position_lo' => $m->position_lo,
            'position_en' => $m->position_en,
            'department'  => $m->department,
            'photo_url'   => $m->photo_url,
            'bio_lo'      => $m->bio_lo,
            'bio_en'      => $m->bio_en,
            'email'       => $m->email,
            'phone'       => $m->phone,
            'term_start'  => $m->term_start,
            'term_end'    => $m->term_end,
            'sort_order'  => $m->sort_order,
            'is_active'   => $m->is_active,
        ];
    }
}
