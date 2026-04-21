<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // ─── ປະເພດໄຟລ໌ທີ່ຮອງຮັບ ───────────────────────────────────────────────────
    private const ALLOWED_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed',
        'application/x-zip-compressed',
        'application/octet-stream', // fallback for zip/rar
    ];

    private const EXT_MAP = [
        'pdf'  => 'PDF',
        'doc'  => 'Word', 'docx' => 'Word',
        'xls'  => 'Excel','xlsx' => 'Excel',
        'ppt'  => 'PPT',  'pptx' => 'PPT',
        'txt'  => 'Text',
        'zip'  => 'ZIP',  'rar'  => 'RAR',
    ];

    // ───────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Document::with('category', 'author')->latest('published_at')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('title_lo', 'like', "%{$q}%")
                                      ->orWhere('title_en', 'like', "%{$q}%")
                                      ->orWhere('title_zh', 'like', "%{$q}%"));
        }
        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('visibility')) {
            $query->where('is_public', $request->visibility === 'public');
        }
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->whereNotNull('published_at')->where('published_at', '<=', now());
            } elseif ($request->status === 'scheduled') {
                $query->whereNotNull('published_at')->where('published_at', '>', now());
            } else {
                $query->whereNull('published_at');
            }
        }

        $documents  = $query->paginate(20)->withQueryString();
        $categories = Category::active()->orderBy('name_lo')->get();
        $typeCounts = Document::selectRaw('file_type, count(*) as total')
                              ->whereNotNull('file_type')
                              ->groupBy('file_type')
                              ->pluck('total', 'file_type');

        return view('admin.documents.index', compact('documents', 'categories', 'typeCounts'));
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::active()->orderBy('name_lo')->get();
        return view('admin.documents.create', compact('categories'));
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'file'           => 'required|file|max:51200|mimetypes:' . implode(',', self::ALLOWED_MIMES),
            'category_id'    => 'nullable|exists:categories,id',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'is_public'      => 'nullable|boolean',
            'published_at'   => 'nullable|date',
        ]);

        $file     = $request->file('file');
        $ext      = strtolower($file->getClientOriginalExtension());
        $path     = $file->store('documents', 'public');
        $fileType = self::EXT_MAP[$ext] ?? strtoupper($ext);
        $sizekb   = (int) ceil($file->getSize() / 1024);

        $publishedAt = null;
        if ($request->boolean('publish_now')) {
            $publishedAt = now();
        } elseif ($request->filled('published_at')) {
            $publishedAt = $request->published_at;
        }

        Document::create([
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'file_url'       => '/storage/' . $path,
            'file_type'      => $fileType,
            'file_size_kb'   => $sizekb,
            'category_id'    => $request->category_id ?: null,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'is_public'      => $request->boolean('is_public'),
            'published_at'   => $publishedAt,
            'author_id'      => auth()->id(),
        ]);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'ເພີ່ມເອກະສານສຳເລັດແລ້ວ');
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function show(Document $document)
    {
        $document->load('category', 'author');
        return view('admin.documents.show', compact('document'));
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function edit(Document $document)
    {
        $categories = Category::active()->orderBy('name_lo')->get();
        return view('admin.documents.edit', compact('document', 'categories'));
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'file'           => 'nullable|file|max:51200|mimetypes:' . implode(',', self::ALLOWED_MIMES),
            'category_id'    => 'nullable|exists:categories,id',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'is_public'      => 'nullable|boolean',
            'published_at'   => 'nullable|date',
        ]);

        $data = [
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'category_id'    => $request->category_id ?: null,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'is_public'      => $request->boolean('is_public'),
        ];

        // ── ໄຟລ໌ໃໝ່ ──
        if ($request->hasFile('file')) {
            // ລຶບໄຟລ໌ເກົ່າຈາກ storage
            $oldPath = str_replace('/storage/', '', $document->file_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $file          = $request->file('file');
            $ext           = strtolower($file->getClientOriginalExtension());
            $path          = $file->store('documents', 'public');
            $data['file_url']     = '/storage/' . $path;
            $data['file_type']    = self::EXT_MAP[$ext] ?? strtoupper($ext);
            $data['file_size_kb'] = (int) ceil($file->getSize() / 1024);
        }

        // ── published_at ──
        if ($request->boolean('publish_now')) {
            $data['published_at'] = now();
        } elseif ($request->filled('published_at')) {
            $data['published_at'] = $request->published_at;
        } elseif ($request->boolean('unpublish')) {
            $data['published_at'] = null;
        } else {
            $data['published_at'] = $document->published_at;
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'ແກ້ໄຂເອກະສານສຳເລັດແລ້ວ');
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function destroy(Document $document)
    {
        // ລຶບໄຟລ໌ຈາກ storage
        $path = str_replace('/storage/', '', $document->file_url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')
                         ->with('success', 'ລຶບເອກະສານສຳເລັດແລ້ວ');
    }

    // ───────────────────────────────────────────────────────────────────────────
    public function download(Document $document)
    {
        $path = str_replace('/storage/', '', $document->file_url);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'ບໍ່ພົບໄຟລ໌ເອກະສານ');
        }

        // ນັບ download_count
        $document->increment('download_count');

        $ext      = pathinfo($path, PATHINFO_EXTENSION);
        $filename = str_replace(['/', ' '], ['-', '_'], $document->title_lo) . '.' . $ext;

        return Storage::disk('public')->download($path, $filename);
    }
}
