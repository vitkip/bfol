<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('category')
            ->public()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s
                ->where('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
                ->orWhere('title_zh', 'like', "%{$q}%")
                ->orWhere('description_lo', 'like', "%{$q}%")
            );
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        $documents  = $query->paginate(15)->withQueryString();
        $categories = Category::active()->where('type', 'document')->orderBy('sort_order')->get();
        $fileTypes  = Document::public()
            ->whereNotNull('file_type')
            ->selectRaw('file_type, count(*) as total')
            ->groupBy('file_type')
            ->orderByDesc('total')
            ->pluck('total', 'file_type');
        $totalCount = Document::public()->whereNotNull('published_at')->where('published_at', '<=', now())->count();

        return view('front.documents.index', compact(
            'documents', 'categories', 'fileTypes', 'totalCount'
        ));
    }

    public function preview(Document $document)
    {
        abort_unless($document->is_public, 403);

        $path = ltrim(str_replace('/storage/', '', $document->file_url), '/');

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'ບໍ່ພົບໄຟລ໌');
        }

        return Storage::disk('public')->response($path, null, [
            'Content-Disposition' => 'inline',
        ]);
    }

    public function download(Document $document)
    {
        abort_unless($document->is_public, 403);

        $path = ltrim(str_replace('/storage/', '', $document->file_url), '/');

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'ບໍ່ພົບໄຟລ໌');
        }

        $document->increment('download_count');

        $ext      = pathinfo($path, PATHINFO_EXTENSION);
        $filename = preg_replace('/\s+/', '_', $document->title_lo) . '.' . $ext;

        return Storage::disk('public')->download($path, $filename);
    }
}
