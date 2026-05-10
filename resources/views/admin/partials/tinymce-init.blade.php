{{--
  ╔══════════════════════════════════════════════════════════════════════╗
  ║   BFOL Admin — Shared TinyMCE v7 Initializer                       ║
  ║   Enterprise-grade rich-text editor — Production Ready              ║
  ╚══════════════════════════════════════════════════════════════════════╝

  USAGE (@push scripts section of any admin form):

    @include('admin.partials.tinymce-init', [
        'tinyEditors'   => [
            ['id' => 'editor-lo', 'placeholder' => 'ຂຽນເນື້ອຫາທີ່ນີ້…'],
            ['id' => 'editor-en', 'placeholder' => 'Write content here…'],
            ['id' => 'editor-zh', 'placeholder' => '在此写内容…'],
        ],
        'tinyUploadUrl' => route('admin.editor.upload'),
        'tinyHeight'    => 480,
    ])

  VARIABLES:
    $tinyEditors    array   Required — list of editor configs
      Each item: ['id' => string, 'placeholder' => string, 'height' => int (opt)]
    $tinyUploadUrl  string  Required — image upload endpoint URL
    $tinyHeight     int     Optional — default editor height in px (default: 480)
    $tinyDraftKey   string  Optional — localStorage prefix override
--}}

@php
  $tinyEditors   = $tinyEditors   ?? [];
  $tinyUploadUrl = $tinyUploadUrl ?? '';
  $tinyHeight    = (int)($tinyHeight ?? 480);
  $tinyDraftKey  = $tinyDraftKey  ?? '';
  $tinyCsrf      = csrf_token();
@endphp

{{-- ══════════ ONCE-PER-PAGE: styles + toast UI + TinyMCE CDN ══════════ --}}
@once

<style>
/* ── TinyMCE editor wrapper ──────────────────────────────────────────── */
.tox-tinymce {
  border-radius: 8px !important;
  border-color: #e8e8e9 !important;
  overflow: hidden !important;
  transition: box-shadow 0.15s ease, border-color 0.15s ease !important;
}
.tox-tinymce:focus-within {
  box-shadow: 0 0 0 3px rgba(0,72,141,0.18) !important;
  border-color: #00488d !important;
}
/* Toolbar */
.tox:not(.tox-tinymce-inline) .tox-editor-header {
  background: #f3f3f4 !important;
  border-bottom: 1px solid #e8e8e9 !important;
  border-radius: 8px 8px 0 0 !important;
  padding: 0 !important;
}
.tox .tox-toolbar__primary { background: transparent !important; }
/* Status bar */
.tox .tox-statusbar {
  background: #f9f9fa !important;
  border-top-color: #e8e8e9 !important;
  border-radius: 0 0 8px 8px !important;
}
/* Responsive iframe */
.tox-tinymce iframe { max-width: 100%; }

/* ── Autosave toast notification ─────────────────────────────────────── */
#tiny-autosave-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  background: #1a1c1d;
  color: #fff;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 12px;
  font-family: 'Phetsarath OT', Inter, sans-serif;
  z-index: 99999;
  opacity: 0;
  transform: translateY(10px) scale(0.97);
  transition: opacity 0.22s ease, transform 0.22s ease;
  pointer-events: none;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.25);
  max-width: 280px;
}
#tiny-autosave-toast.show {
  opacity: 1;
  transform: translateY(0) scale(1);
}

/* ── Word/Char count meta bar ────────────────────────────────────────── */
.tiny-meta-bar {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 5px 10px;
  background: #f3f3f4;
  border: 1px solid #e8e8e9;
  border-top: none;
  border-radius: 0 0 8px 8px;
  font-size: 11px;
  color: #727783;
  font-family: Inter, sans-serif;
}
.tiny-meta-bar .tiny-stat { display: flex; align-items: center; gap: 4px; }
.tiny-meta-bar .tiny-dot  { width: 3px; height: 3px; background: #c2c6d4; border-radius: 50%; }

/* ── Dark mode overrides ─────────────────────────────────────────────── */
@media (prefers-color-scheme: dark) {
  .tox-tinymce { border-color: #3a3d40 !important; }
}
</style>

{{-- Autosave toast element --}}
<div id="tiny-autosave-toast" role="status" aria-live="polite">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
       stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M20 6L9 17l-5-5"/>
  </svg>
  <span id="tiny-autosave-msg">Draft saved</span>
</div>

<script>
/* ── Toast helper (global) ────────────────────────────────────────────── */
(function () {
  let _toastTimer;
  window._tinyToast = function (msg, type) {
    const el  = document.getElementById('tiny-autosave-toast');
    const txt = document.getElementById('tiny-autosave-msg');
    if (!el || !txt) return;
    txt.textContent = msg || 'Saved';
    el.style.background = type === 'error' ? '#dc2626' : '#1a1c1d';
    el.classList.add('show');
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(function () { el.classList.remove('show'); }, 3000);
  };
})();
</script>

{{-- TinyMCE v7 — loaded synchronously from jsDelivr CDN (no API key required) --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>

@endonce

{{-- ══════════ PER-INCLUDE: initialise editors for this form ══════════ --}}
<script>
(function () {
  'use strict';

  /* ─── Config ─────────────────────────────────────────────────────── */
  var UPLOAD_URL  = {!! json_encode($tinyUploadUrl) !!};
  var CSRF_TOKEN  = {!! json_encode($tinyCsrf) !!};
  var DRAFT_KEY   = {!! $tinyDraftKey ? json_encode($tinyDraftKey) : "'bfol-'" !!};

  /* ─── Dark-mode detection ────────────────────────────────────────── */
  var IS_DARK = document.documentElement.classList.contains('dark') ||
                window.matchMedia('(prefers-color-scheme: dark)').matches;

  /* ─── XHR upload handler with progress ──────────────────────────── */
  function makeUploadHandler(url, token) {
    return function (blobInfo, progress) {
      return new Promise(function (resolve, reject) {
        var fd  = new FormData();
        fd.append('upload', blobInfo.blob(), blobInfo.filename());

        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function (e) {
          if (e.lengthComputable) progress(Math.round(e.loaded / e.total * 100));
        };

        xhr.onload = function () {
          if (xhr.status < 200 || xhr.status >= 300) {
            var msg = 'Upload failed (' + xhr.status + ')';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch (e) {}
            window._tinyToast('Upload error: ' + msg, 'error');
            reject({ message: msg, remove: true });
            return;
          }
          try {
            var data = JSON.parse(xhr.responseText);
            if (data.url) {
              resolve(data.url);
            } else {
              var errMsg = (data.errors && data.errors.upload) ? data.errors.upload[0] : (data.message || 'Upload failed');
              window._tinyToast(errMsg, 'error');
              reject({ message: errMsg, remove: true });
            }
          } catch (e) {
            reject({ message: 'Invalid server response', remove: true });
          }
        };

        xhr.onerror = function () {
          window._tinyToast('Network error — upload failed', 'error');
          reject({ message: 'Network error during upload', remove: true });
        };

        xhr.send(fd);
      });
    };
  }

  /* ─── Server-side session autosave (60s, silent fallback) ──────── */
  @if(Route::has('admin.editor.autosave'))
  function setupServerAutosave(editor, key) {
    var dirty = false;
    editor.on('input change', function () { dirty = true; });

    setInterval(function () {
      if (!dirty || !editor.initialized) return;
      dirty = false;

      fetch('{!! route("admin.editor.autosave") !!}', {
        method:    'POST',
        headers:   {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept':       'application/json',
        },
        body:      JSON.stringify({ key: key, content: editor.getContent() }),
        keepalive: true,
      })
      .then(function (r) { if (r.ok) window._tinyToast('Draft auto-saved'); })
      .catch(function () { /* silent — localStorage backup covers this */ });
    }, 60000);
  }
  @else
  function setupServerAutosave() {}
  @endif

  /* ─── TinyMCE base config ────────────────────────────────────────── */
  var BASE_HEIGHT  = {{ $tinyHeight }};
  var _uploadHandler = makeUploadHandler(UPLOAD_URL, CSRF_TOKEN);

  var CONTENT_STYLE = [
    "@import url('https://fonts.googleapis.com/css2?family=Phetsarath+OT&display=swap');",
    "body{font-family:'Phetsarath OT',Inter,sans-serif;font-size:14px;margin:10px 14px;line-height:1.8;color:#1a1c1d;}",
    "img{max-width:100%;height:auto;border-radius:4px;}",
    "table{border-collapse:collapse;width:100%;margin:12px 0;}",
    "td,th{border:1px solid #e2e2e3;padding:8px 12px;vertical-align:top;}",
    "th{background:#f3f3f4;font-weight:600;}",
    "pre{background:#f3f3f4;padding:12px 16px;border-radius:6px;overflow-x:auto;font-size:12px;line-height:1.6;}",
    "blockquote{border-left:4px solid #00488d;margin:12px 0;padding:8px 16px;background:#f9f9fa;border-radius:0 6px 6px 0;color:#424752;}",
    "a{color:#00488d;}",
    "h1{font-size:2em;}h2{font-size:1.5em;}h3{font-size:1.25em;}h4{font-size:1.1em;}",
    "h1,h2,h3,h4{color:#1a1c1d;line-height:1.35;margin-top:1.2em;margin-bottom:0.4em;}",
    ".video-wrapper{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;max-width:100%;border-radius:6px;}",
    ".video-wrapper iframe{position:absolute;top:0;left:0;width:100%;height:100%;border:0;}",
    "code:not(pre code){background:#f0f0f1;padding:2px 5px;border-radius:3px;font-size:12px;font-family:monospace;}",
    "hr{border:none;border-top:2px solid #e8e8e9;margin:20px 0;}",
    IS_DARK ? [
      "body{background:#1e2022;color:#e8e8e9;}",
      "blockquote{background:#2a2d30;border-left-color:#5ba3f5;}",
      "pre{background:#2a2d30;}",
      "th{background:#2a2d30;}",
      "td,th{border-color:#3a3d40;}",
      "a{color:#5ba3f5;}",
      "h1,h2,h3,h4{color:#e8e8e9;}",
      "code:not(pre code){background:#2a2d30;}",
    ].join('') : '',
  ].filter(Boolean).join(' ');

  var TINY_BASE = {
    license_key: 'gpl',

    height:     BASE_HEIGHT,
    min_height: 280,
    resize:     true,

    promotion:  false,
    branding:   false,

    /* ── Menubar ─────────────────────────────── */
    menubar: 'edit view insert format tools table',

    /* ── Plugins ─────────────────────────────── */
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
      'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
      'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount',
      'help', 'autosave', 'codesample', 'emoticons',
      'directionality', 'quickbars', 'nonbreaking', 'pagebreak',
    ],

    /* ── Toolbar (sliding = wraps on small screens) ──────────────── */
    toolbar: [
      'undo redo',
      'blocks fontfamily fontsize',
      'bold italic underline strikethrough',
      'forecolor backcolor removeformat',
      'alignleft aligncenter alignright alignjustify',
      'bullist numlist outdent indent',
      'link image media table codesample emoticons charmap',
      'ltr rtl',
      'searchreplace code fullscreen preview help',
    ].join(' | '),

    toolbar_mode:          'sliding',
    toolbar_sticky:        true,
    toolbar_sticky_offset: 57,   /* height of admin sticky topbar */

    /* ── Quickbars (floating selection toolbar) ──────────────────── */
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
    quickbars_image_toolbar:     'alignleft aligncenter alignright | rotateleft rotateright | imageoptions',
    quickbars_insert_toolbar:    false,

    /* ── Auto-save to localStorage ───────────────────────────────── */
    autosave_interval:           '30s',
    autosave_restore_when_empty: true,
    autosave_retention:          '120m',

    /* ── Image handling ──────────────────────────────────────────── */
    images_upload_handler: _uploadHandler,
    images_reuse_filename: false,
    image_caption:         true,
    image_advtab:          true,
    image_description:     true,

    /* ── Table ───────────────────────────────────────────────────── */
    table_default_styles:   {},
    table_class_list: [
      { title: 'Default',   value: '' },
      { title: 'Bordered',  value: 'table-bordered' },
      { title: 'Striped',   value: 'table-striped' },
    ],

    /* ── Links ───────────────────────────────────────────────────── */
    link_assume_external_targets: true,
    link_default_target:          '_blank',
    link_rel_list: [
      { title: 'None',                value: '' },
      { title: 'No Follow',           value: 'nofollow' },
      { title: 'No Opener + Noreferrer', value: 'noopener noreferrer' },
    ],

    /* ── Media (YouTube + video embeds) ──────────────────────────── */
    media_live_embeds:   true,
    media_url_resolver:  function (data, resolve) {
      var url      = data.url || '';
      var ytMatch  = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s?]+)/);
      if (ytMatch) {
        resolve({
          html: '<div class="video-wrapper">' +
                '<iframe src="https://www.youtube.com/embed/' + ytMatch[1] +
                '" allow="accelerometer;autoplay;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>' +
                '</div>',
        });
        return;
      }
      resolve({ html: '' });
    },

    /* ── Code sample languages ───────────────────────────────────── */
    codesample_global_prismjs: false,
    codesample_languages: [
      { text: 'HTML/XML',    value: 'markup' },
      { text: 'JavaScript',  value: 'javascript' },
      { text: 'CSS',         value: 'css' },
      { text: 'PHP',         value: 'php' },
      { text: 'Python',      value: 'python' },
      { text: 'Bash/Shell',  value: 'bash' },
      { text: 'SQL',         value: 'sql' },
      { text: 'JSON',        value: 'json' },
    ],

    /* ── Block & font formats ────────────────────────────────────── */
    block_formats: 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Preformatted=pre',

    font_family_formats: [
      'Phetsarath OT=Phetsarath OT,sans-serif',
      'Noto Sans Lao=Noto Sans Lao,sans-serif',
      'Inter=Inter,sans-serif',
      'Arial=arial,helvetica,sans-serif',
      'Georgia=georgia,palatino,serif',
      'Times New Roman=times new roman,times,serif',
      'Courier New=courier new,courier,monospace',
    ].join(';'),

    font_size_formats: '10px 11px 12px 13px 14px 15px 16px 18px 20px 24px 28px 32px 36px 48px',

    /* ── Colour palette (brand + common) ────────────────────────── */
    color_map: [
      '1a1c1d', 'Black',
      '00488d', 'Primary Blue',
      '005fb8', 'Deep Blue',
      '1d4ed8', 'Blue',
      'dc2626', 'Red',
      'b45309', 'Amber',
      '16a34a', 'Green',
      '0891b2', 'Cyan',
      '7c3aed', 'Purple',
      'ffffff', 'White',
      'f3f3f4', 'Light Gray',
      '727783', 'Gray',
      '1a1c1d', 'Near Black',
    ],
    color_cols: 5,

    /* ── URL handling ────────────────────────────────────────────── */
    convert_urls:   false,
    relative_urls:  false,

    /* ── History ─────────────────────────────────────────────────── */
    custom_undo_redo_levels: 60,

    /* ── Paste ───────────────────────────────────────────────────── */
    paste_data_images: true,

    /* ── Skin ────────────────────────────────────────────────────── */
    skin:        IS_DARK ? 'oxide-dark' : 'oxide',
    content_css: IS_DARK ? 'dark'       : 'default',

    /* ── Content styles ──────────────────────────────────────────── */
    content_style: CONTENT_STYLE,

    /* ── Setup callback (runs once per editor instance) ──────────── */
    setup: function (editor) {

      /* Auto-save toast */
      editor.on('StoreDraft', function () {
        window._tinyToast('Draft saved locally');
      });
      editor.on('RestoreDraft', function () {
        window._tinyToast('Draft restored');
      });

      /* Word / char count meta bar update */
      editor.on('wordcount:update', function (e) {
        var bar = document.getElementById('tiny-meta-' + editor.id);
        if (!bar) return;
        bar.querySelector('.tiny-words').textContent = (e.wordCount && e.wordCount.words) || '0';
        bar.querySelector('.tiny-chars').textContent = (e.wordCount && e.wordCount.characters) || '0';
      });

      /* Server-side session autosave */
      setupServerAutosave(editor, DRAFT_KEY + editor.id);
    },
  };

  /* ─── Initialise each editor ─────────────────────────────────────── */
  @foreach($tinyEditors as $ed)
  (function () {
    var cfg = Object.assign({}, TINY_BASE, {
      selector:        '#{{ $ed['id'] }}',
      autosave_prefix: DRAFT_KEY + '{{ $ed['id'] }}-{path}-',
      @if(!empty($ed['placeholder']))
      placeholder:     {!! json_encode($ed['placeholder']) !!},
      @endif
      @if(!empty($ed['height']))
      height:          {{ (int)$ed['height'] }},
      @endif
    });

    tinymce.init(cfg).then(function () {
      /* Insert meta bar below the editor */
      var wrapper = document.querySelector('.tox-tinymce:has(> .tox-editor-container #{{ $ed['id'] }}_ifr)');
      if (!wrapper) {
        /* Fallback: find wrapper via editor instance */
        var ed = tinymce.get('{{ $ed['id'] }}');
        if (ed) wrapper = ed.getContainer();
      }
      if (wrapper) {
        var bar = document.createElement('div');
        bar.id = 'tiny-meta-{{ $ed['id'] }}';
        bar.className = 'tiny-meta-bar';
        bar.innerHTML =
          '<span class="tiny-stat">' +
            '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h16M4 18h10"/></svg>' +
            '<span class="tiny-words">0</span> words' +
          '</span>' +
          '<span class="tiny-dot"></span>' +
          '<span class="tiny-stat">' +
            '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 12h8M12 8v8"/></svg>' +
            '<span class="tiny-chars">0</span> chars' +
          '</span>';
        wrapper.parentNode && wrapper.parentNode.insertBefore(bar, wrapper.nextSibling);
      }
    });
  })();
  @endforeach

  /* ─── Global helpers ─────────────────────────────────────────────── */

  /* Call before form submit to flush TinyMCE content to <textarea> */
  window.syncTinyMCE = function () {
    if (typeof tinymce !== 'undefined') tinymce.triggerSave();
  };

  /* Refresh editor layout after Alpine tab switch reveals a hidden editor */
  window.refreshTinyMCE = function (editorId) {
    if (typeof tinymce === 'undefined') return;
    /* Dispatch resize so TinyMCE recalculates its iframe dimensions */
    setTimeout(function () {
      window.dispatchEvent(new Event('resize'));
      /* Also trigger the editor's own resize for the specific instance */
      var ed = editorId ? tinymce.get(editorId) : null;
      if (ed && ed.initialized) {
        try { ed.fire('ResizeEditor'); } catch (ex) {}
      }
    }, 30);
  };

})();
</script>
