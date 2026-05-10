<?php

namespace App\Services;

/**
 * Wraps ezyang/htmlpurifier with a configuration tuned for TinyMCE v7 output.
 *
 * Security decisions:
 *  - <iframe> is allowed only when src matches the YouTube / YouTube-nocookie
 *    domain whitelist (HTML.SafeIframe + URI.SafeIframeRegexp).
 *  - <a target="_blank"> automatically gains rel="noopener noreferrer" to
 *    prevent reverse tabnapping via window.opener.
 *  - <script>, <style>, event handlers (onclick etc.) are always stripped.
 *  - Only a curated subset of CSS properties is allowed (no url(), no expression()).
 *  - HTML5 media elements (video, audio, source) are registered via the
 *    HTMLPurifier definition API — HTML.Allowed alone cannot handle them without
 *    generating "unknown element" notices.
 */
class HtmlPurifier
{
    private static ?\HTMLPurifier $instance = null;

    /* ── Allowed iframe origins (TinyMCE media plugin / YouTube embeds) ─ */
    private const SAFE_IFRAME_REGEXP =
        '%^https://(www\.youtube(-nocookie)?\.com/embed/|www\.youtube\.com/watch)%';

    /* ── CSS properties allowed on inline styles ─────────────────────── */
    private const ALLOWED_CSS = [
        'text-align', 'text-decoration', 'text-transform', 'text-indent',
        'vertical-align', 'white-space', 'word-break', 'word-wrap',
        'color', 'background-color',
        'font-size', 'font-weight', 'font-style', 'font-family', 'line-height',
        'width', 'height', 'max-width', 'min-width', 'max-height', 'min-height',
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border', 'border-radius', 'border-collapse',
        'float', 'clear', 'display', 'overflow',
        'position', 'top', 'right', 'bottom', 'left',  // needed for video-wrapper
    ];

    /* ─────────────────────────────────────────────────────────────────── */

    private static function make(): \HTMLPurifier
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = \HTMLPurifier_Config::createDefault();

        /* ── Allowed HTML elements + attributes ─────────────────────── */
        // HTML5 elements (video, audio, source) are registered via addElement()
        // in the definition block below. Listing them here as well causes
        // HTMLPurifier to whitelist them in the final pass without generating
        // "unknown element" notices, because by finalization time the definition
        // already knows about them.
        // We do NOT set HTML.AllowedAttributes — it would conflict with this
        // config and its processing of custom HTML5 attributes would cause notices.
        $config->set('HTML.Allowed',
            /* Text-level semantics */
            'p,br,strong,b,em,i,u,s,strike,del,ins,sup,sub,mark,small,abbr[title],' .

            /* Headings */
            'h1,h2,h3,h4,h5,h6,' .

            /* Lists */
            'ul,ol,li,dl,dt,dd,' .

            /* Grouping / sectioning */
            'blockquote,pre[class|style],code[class],kbd,samp,var,' .
            'div[id|class|style],span[id|class|style|dir],' .
            'section[id|class],article[id|class],' .
            'figure,figcaption,' .
            'details,summary,' .
            'hr,' .

            /* Tables */
            'table[id|class|style|border|cellpadding|cellspacing],' .
            'thead,tbody,tfoot,' .
            'tr[class|style],' .
            'th[class|style|scope|colspan|rowspan],' .
            'td[class|style|colspan|rowspan],' .
            'caption,' .

            /* Images — loading is registered via addAttribute() below */
            'img[src|alt|width|height|style|class|loading],' .

            /* HTML5 media — registered via addElement() below */
            'video[src|controls|width|height|style|poster|preload],' .
            'audio[src|controls|style|preload],' .
            'source[src|type],' .

            /* Iframe — allowfullscreen/allow/loading registered via addAttribute() */
            'iframe[src|class|style|width|height|frameborder|title|allowfullscreen|allow|loading],' .

            /* Hyperlinks — target is enabled via Attr.AllowedFrameTargets below */
            'a[href|title|target|rel|id|class|style]'
        );

        /* ── Allow target="_blank" (and variants) on anchor elements ─── */
        // Without this, HTMLPurifier strips the target attribute entirely because
        // HTML 4.01 Strict (its default doctype) does not permit target on <a>.
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_top', '_parent']);

        /* ── URI / link security ─────────────────────────────────────── */
        $config->set('URI.AllowedSchemes', [
            'http'   => true,
            'https'  => true,
            'mailto' => true,
        ]);

        /* ── Safe iframe: allow only whitelisted video domains ───────── */
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', self::SAFE_IFRAME_REGEXP);

        /* ── CSS ─────────────────────────────────────────────────────── */
        $config->set('CSS.AllowedProperties', implode(',', self::ALLOWED_CSS));

        /* ── Auto-format ─────────────────────────────────────────────── */
        $config->set('AutoFormat.AutoParagraph', false);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('Output.TidyFormat', false);

        /* ── Definition cache — bump DefinitionRev on any schema change ─ */
        $config->set('HTML.DefinitionID',  'bfol-cms-v5');
        $config->set('HTML.DefinitionRev', 5);

        /* ── Serializer cache ────────────────────────────────────────── */
        $cacheDir = storage_path('framework/cache/htmlpurifier');
        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cacheDir);

        /* ── HTML5 custom element + attribute definitions ────────────── */
        // maybeGetRawHTMLDefinition() returns the mutable definition only when
        // it is NOT already cached. Returning null means a valid cached copy
        // is in use — no modifications needed.
        //
        // All elements listed in HTML.Allowed that are NOT in HTMLPurifier's
        // HTML 4.01 base definition MUST be registered here first, otherwise
        // setupConfigStuff() emits E_USER_WARNING for each unknown element
        // (which Laravel's error handler promotes to a fatal exception).
        if ($def = $config->maybeGetRawHTMLDefinition()) {

            /* ── HTML5 semantic / structural elements ────────────────── */
            $def->addElement('mark',       'Inline', 'Inline', 'Common', []);
            $def->addElement('figure',     'Block',  'Flow',   'Common', []);
            $def->addElement('figcaption', 'Block',  'Flow',   'Common', []);
            $def->addElement('section',    'Block',  'Flow',   'Common', []);
            $def->addElement('article',    'Block',  'Flow',   'Common', []);
            $def->addElement('summary',    'Block',  'Flow',   'Common', []);
            $def->addElement('details',    'Block',  'Flow',   'Common', [
                'open' => new \HTMLPurifier_AttrDef_HTML_Bool('open'),
            ]);

            /* ── HTML5 media elements ────────────────────────────────── */
            /* <source> must be registered before video/audio reference it */
            $def->addElement('source', 'Block', 'Empty', 'Core', [
                'src'  => 'URI',
                'type' => 'Text',
            ]);

            /* <video> */
            $def->addElement('video', 'Block', 'Optional: (source | Flow)*', 'Common', [
                'src'      => 'URI',
                'controls' => new \HTMLPurifier_AttrDef_HTML_Bool('controls'),
                'width'    => 'Length',
                'height'   => 'Length',
                'poster'   => 'URI',
                'preload'  => new \HTMLPurifier_AttrDef_Enum(['auto', 'metadata', 'none'], false),
            ]);

            /* <audio> */
            $def->addElement('audio', 'Block', 'Optional: (source | Flow)*', 'Common', [
                'src'      => 'URI',
                'controls' => new \HTMLPurifier_AttrDef_HTML_Bool('controls'),
                'preload'  => new \HTMLPurifier_AttrDef_Enum(['auto', 'metadata', 'none'], false),
            ]);

            /* ── New attributes on existing HTML4 elements ───────────── */
            /* img.loading — lazy-load hint not in HTML4 definition */
            $def->addAttribute('img', 'loading',
                new \HTMLPurifier_AttrDef_Enum(['lazy', 'eager', 'auto'], false));

            /* iframe — HTML5 / embed-API attributes absent from HTML4 */
            $def->addAttribute('iframe', 'allowfullscreen',
                new \HTMLPurifier_AttrDef_HTML_Bool('allowfullscreen'));
            $def->addAttribute('iframe', 'allow',
                new \HTMLPurifier_AttrDef_Text());
            $def->addAttribute('iframe', 'loading',
                new \HTMLPurifier_AttrDef_Enum(['lazy', 'eager', 'auto'], false));
        }

        self::$instance = new \HTMLPurifier($config);

        return self::$instance;
    }

    /**
     * Purify a single HTML string.
     *
     * After HTMLPurifier cleans the markup, a DOM post-pass injects
     * rel="noopener noreferrer" on every <a target="_blank"> link to prevent
     * reverse-tabnapping (window.opener access from the opened page).
     *
     * Returns null unchanged if input is null or empty.
     */
    public static function clean(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        $clean = self::make()->purify($html);

        return self::patchBlankTargetLinks($clean);
    }

    /**
     * Purify multiple HTML fields in a data array, in-place.
     *
     * @param array<string,mixed> $data   The data array (passed by reference)
     * @param string[]            $fields Keys of fields that contain HTML
     */
    public static function cleanFields(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = self::clean($data[$field]);
            }
        }
    }

    /**
     * Inject rel="noopener noreferrer" on all <a target="_blank"> links.
     *
     * Uses DOMDocument to avoid fragile regex-on-HTML manipulation.
     * Only runs when the content actually contains a target="_blank" link.
     */
    private static function patchBlankTargetLinks(string $html): string
    {
        if ($html === '' || stripos($html, '_blank') === false) {
            return $html;
        }

        $charset = mb_detect_encoding($html, 'UTF-8', true) ?: 'UTF-8';
        $wrapped  = '<?xml encoding="' . $charset . '">'
                  . '<html><body>' . $html . '</body></html>';

        $dom = new \DOMDocument('1.0', $charset);
        $dom->recover            = true;
        $dom->strictErrorChecking = false;

        libxml_use_internal_errors(true);
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName('a') as $anchor) {
            /** @var \DOMElement $anchor */
            if (strtolower($anchor->getAttribute('target')) !== '_blank') {
                continue;
            }

            $existing = $anchor->getAttribute('rel');
            $parts    = array_filter(array_map('trim', explode(' ', $existing)));

            foreach (['noopener', 'noreferrer'] as $token) {
                if (! in_array($token, $parts, true)) {
                    $parts[] = $token;
                }
            }

            $anchor->setAttribute('rel', implode(' ', $parts));
        }

        /* Extract only the <body> content, dropping the wrapping skeleton */
        $body  = $dom->getElementsByTagName('body')->item(0);
        $inner = '';
        if ($body) {
            foreach ($body->childNodes as $child) {
                $inner .= $dom->saveHTML($child);
            }
        }

        return $inner !== '' ? $inner : $html;
    }
}
