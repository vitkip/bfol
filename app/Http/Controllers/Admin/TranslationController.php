<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TranslationProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TranslationController extends Controller
{
    private const STATUSES = [
        'in_progress' => ['ກຳລັງດຳເນີນ', 'bg-blue-100 text-blue-700'],
        'reviewing'   => ['ກວດທານ',        'bg-amber-100 text-amber-700'],
        'completed'   => ['ສຳເລັດ',         'bg-emerald-100 text-emerald-700'],
        'published'   => ['ເຜີຍແຜ່',        'bg-primary/10 text-primary'],
    ];

    public function index(Request $request)
    {
        $query = TranslationProject::latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('title_lo', 'like', "%{$q}%")
                                      ->orWhere('title_en', 'like', "%{$q}%")
                                      ->orWhere('translator', 'like', "%{$q}%"));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $projects = $query->paginate(20)->withQueryString();
        $years    = TranslationProject::selectRaw('DISTINCT year')->whereNotNull('year')
                        ->orderByDesc('year')->pluck('year');

        return view('admin.translations.index', compact('projects', 'years'));
    }

    public function create()
    {
        return view('admin.translations.create', ['statuses' => self::STATUSES]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'source_language'=> 'nullable|string|max:60',
            'target_language'=> 'nullable|string|max:60',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'translator'     => 'nullable|string|max:200',
            'year'           => 'nullable|integer|min:1900|max:2100',
            'status'         => 'required|in:in_progress,reviewing,completed,published',
            'doc_file'       => 'nullable|file|max:20480|mimes:pdf,doc,docx',
            'document_url'   => 'nullable|string|max:500',
        ]);

        $docUrl = $request->document_url;
        if ($request->hasFile('doc_file')) {
            $docUrl = '/storage/' . $request->file('doc_file')->store('translations', 'public');
        }

        TranslationProject::create([
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'source_language'=> $request->source_language,
            'target_language'=> $request->target_language,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'translator'     => $request->translator,
            'year'           => $request->year ?: null,
            'status'         => $request->status,
            'document_url'   => $docUrl,
        ]);

        return redirect()->route('admin.translations.index')
                         ->with('success', 'ເພີ່ມໂຄງການແປສຳເລັດ');
    }

    public function show(TranslationProject $translation)
    {
        return redirect()->route('admin.translations.edit', $translation);
    }

    public function edit(TranslationProject $translation)
    {
        return view('admin.translations.edit', [
            'project'  => $translation,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, TranslationProject $translation)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'source_language'=> 'nullable|string|max:60',
            'target_language'=> 'nullable|string|max:60',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'translator'     => 'nullable|string|max:200',
            'year'           => 'nullable|integer|min:1900|max:2100',
            'status'         => 'required|in:in_progress,reviewing,completed,published',
            'doc_file'       => 'nullable|file|max:20480|mimes:pdf,doc,docx',
            'document_url'   => 'nullable|string|max:500',
        ]);

        $docUrl = $request->document_url ?: $translation->document_url;
        if ($request->hasFile('doc_file')) {
            if ($translation->document_url && str_starts_with($translation->document_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $translation->document_url));
            }
            $docUrl = '/storage/' . $request->file('doc_file')->store('translations', 'public');
        }
        if ($request->boolean('remove_doc')) {
            if ($translation->document_url && str_starts_with($translation->document_url, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $translation->document_url));
            }
            $docUrl = null;
        }

        $translation->update([
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'source_language'=> $request->source_language,
            'target_language'=> $request->target_language,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'translator'     => $request->translator,
            'year'           => $request->year ?: null,
            'status'         => $request->status,
            'document_url'   => $docUrl,
        ]);

        return redirect()->route('admin.translations.index')
                         ->with('success', 'ແກ້ໄຂໂຄງການແປສຳເລັດ');
    }

    public function destroy(TranslationProject $translation)
    {
        if ($translation->document_url && str_starts_with($translation->document_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $translation->document_url));
        }
        $translation->delete();
        return redirect()->route('admin.translations.index')->with('success', 'ລຶບໂຄງການແປສຳເລັດ');
    }
}
