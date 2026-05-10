<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class EditorUploadController extends Controller
{
    /* Max upload size: 5 MB in KB (Laravel validator uses KB) */
    private const MAX_KB = 5120;

    /* Downscale if image exceeds this dimension */
    private const MAX_DIM = 1920;

    /* WebP encoding quality */
    private const WEBP_Q = 82;

    private const ALLOWED_MIMES = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif',
    ];

    /**
     * Handle TinyMCE image upload (images_upload_handler).
     *
     * POST /admin/editor/upload  [field: upload]
     * → { "url": "https://…/storage/editor/uuid.webp" }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', 'file', 'max:' . self::MAX_KB, 'mimes:jpeg,jpg,png,webp,gif'],
        ]);

        $file = $request->file('upload');

        /* Secondary real-content MIME check (not just extension) */
        if (! in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            return response()->json(['message' => 'File type not allowed.'], 422);
        }

        try {
            /* intervention/image v4 API: decode() not read() */
            $img = Image::decode($file->getRealPath());

            /* Fix phone photo rotation via EXIF */
            $img->orient();

            /* Downscale oversized images while preserving aspect ratio */
            if ($img->width() > self::MAX_DIM || $img->height() > self::MAX_DIM) {
                $img->scaleDown(self::MAX_DIM, self::MAX_DIM);
            }

            /* Encode — try WebP first, fall back to JPEG if unsupported */
            try {
                $encoded  = $img->encode(new WebpEncoder(self::WEBP_Q));
                $ext      = 'webp';
            } catch (\Throwable) {
                $encoded  = $img->encode(new JpegEncoder(85));
                $ext      = 'jpg';
            }

            $path = 'editor/' . Str::uuid() . '.' . $ext;
            Storage::disk('public')->put($path, (string) $encoded);

            Log::info('Editor upload', [
                'user' => auth()->id(),
                'path' => $path,
                'kb'   => round($file->getSize() / 1024, 1),
                'ext'  => $ext,
            ]);

            return response()->json(['url' => Storage::disk('public')->url($path)]);

        } catch (\Throwable $e) {
            Log::error('Editor upload error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Image processing failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Server-side session autosave backup (called by JS every 60 s).
     *
     * POST /admin/editor/autosave
     * { "key": "editor-lo", "content": "<p>…</p>" }
     */
    public function autosave(Request $request): JsonResponse
    {
        $request->validate([
            'key'     => ['required', 'string', 'max:120', 'regex:/^[a-zA-Z0-9\-_]+$/'],
            'content' => ['nullable', 'string', 'max:600000'],
        ]);

        session()->put('editor_draft_' . $request->key, [
            'content'  => $request->input('content', ''),
            'saved_at' => now()->toIso8601String(),
            'user_id'  => auth()->id(),
        ]);

        return response()->json(['status' => 'saved', 'saved_at' => now()->toIso8601String()]);
    }

    /**
     * Retrieve a session draft for explicit restore UI.
     *
     * GET /admin/editor/draft?key=editor-lo
     */
    public function getDraft(Request $request): JsonResponse
    {
        $request->validate([
            'key' => ['required', 'string', 'max:120', 'regex:/^[a-zA-Z0-9\-_]+$/'],
        ]);

        $draft = session()->get('editor_draft_' . $request->key);

        return response()->json($draft
            ? ['found' => true, 'content' => $draft['content'] ?? '', 'saved_at' => $draft['saved_at'] ?? null]
            : ['found' => false]
        );
    }
}
