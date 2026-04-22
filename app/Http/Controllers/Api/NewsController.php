<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author', 'tags'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%$s%")
                  ->orWhere('title_en', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $news = $query->paginate($request->get('per_page', 15));

        return response()->json($news->through(fn($n) => $this->format($n)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'     => ['required', 'string', 'max:300'],
            'title_en'     => ['nullable', 'string', 'max:300'],
            'title_zh'     => ['nullable', 'string', 'max:300'],
            'excerpt_lo'   => ['nullable', 'string', 'max:1000'],
            'excerpt_en'   => ['nullable', 'string', 'max:1000'],
            'excerpt_zh'   => ['nullable', 'string', 'max:1000'],
            'content_lo'   => ['required', 'string'],
            'content_en'   => ['nullable', 'string'],
            'content_zh'   => ['nullable', 'string'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured'  => ['boolean'],
            'is_urgent'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'tag_ids'      => ['nullable', 'array'],
            'tag_ids.*'    => ['exists:tags,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        }

        $data['author_id'] = $request->user()->id;

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news = News::create($data);
        $news->tags()->sync($request->input('tag_ids', []));

        return response()->json($this->format($news->fresh(['category', 'author', 'tags'])), 201);
    }

    public function show(News $news)
    {
        return response()->json($this->format($news->load(['category', 'author', 'tags'])));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title_lo'     => ['required', 'string', 'max:300'],
            'title_en'     => ['nullable', 'string', 'max:300'],
            'title_zh'     => ['nullable', 'string', 'max:300'],
            'excerpt_lo'   => ['nullable', 'string', 'max:1000'],
            'excerpt_en'   => ['nullable', 'string', 'max:1000'],
            'excerpt_zh'   => ['nullable', 'string', 'max:1000'],
            'content_lo'   => ['required', 'string'],
            'content_en'   => ['nullable', 'string'],
            'content_zh'   => ['nullable', 'string'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured'  => ['boolean'],
            'is_urgent'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'tag_ids'      => ['nullable', 'array'],
            'tag_ids.*'    => ['exists:tags,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        if ($data['status'] === 'published' && ! $news->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news->update($data);
        $news->tags()->sync($request->input('tag_ids', []));

        return response()->json($this->format($news->fresh(['category', 'author', 'tags'])));
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();

        return response()->json(['message' => 'ລຶບຂ່າວສຳເລັດ']);
    }

    public function updateStatus(Request $request, News $news)
    {
        $request->validate(['status' => ['required', Rule::in(['draft', 'published', 'archived'])]]);
        $news->update(['status' => $request->status]);

        return response()->json(['message' => 'ອັບເດດສຳເລັດ', 'status' => $news->status]);
    }

    private function format(News $n): array
    {
        return [
            'id'           => $n->id,
            'title_lo'     => $n->title_lo,
            'title_en'     => $n->title_en,
            'title_zh'     => $n->title_zh,
            'excerpt_lo'   => $n->excerpt_lo,
            'excerpt_en'   => $n->excerpt_en,
            'slug'         => $n->slug,
            'content_lo'   => $n->content_lo,
            'content_en'   => $n->content_en,
            'thumbnail'    => $n->thumbnail ? asset('storage/' . $n->thumbnail) : null,
            'status'       => $n->status,
            'is_featured'  => $n->is_featured,
            'is_urgent'    => $n->is_urgent,
            'published_at' => $n->published_at?->toDateString(),
            'view_count'   => $n->view_count,
            'category'     => $n->category ? ['id' => $n->category->id, 'name' => $n->category->name_lo] : null,
            'author'       => $n->author ? ['id' => $n->author->id, 'name' => $n->author->full_name_lo ?: $n->author->email] : null,
            'tags'         => $n->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name_lo]),
            'created_at'   => $n->created_at?->toDateTimeString(),
            'updated_at'   => $n->updated_at?->toDateTimeString(),
        ];
    }
}
