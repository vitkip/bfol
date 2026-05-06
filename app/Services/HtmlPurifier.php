<?php

namespace App\Services;

class HtmlPurifier
{
    private static ?\HTMLPurifier $instance = null;

    private static function make(): \HTMLPurifier
    {
        if (self::$instance === null) {
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed',
                'p,br,strong,b,em,i,u,s,strike,del,ins,sup,sub,' .
                'h1,h2,h3,h4,h5,h6,' .
                'ul,ol,li,dl,dt,dd,' .
                'blockquote,pre,code,kbd,' .
                'table,thead,tbody,tfoot,tr,th,td,' .
                'a[href|title|target],img[src|alt|width|height|style],' .
                'figure,figcaption,caption,' .
                'div[class|style],span[class|style],section[class],' .
                'hr,mark'
            );
            $config->set('HTML.AllowedAttributes', 'a.href,a.title,a.target,img.src,img.alt,img.width,img.height,img.style,*.class,*.style');
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $config->set('CSS.AllowedProperties', 'text-align,color,background-color,width,height,float,margin,padding,font-size,font-weight,font-style,text-decoration,border');
            $config->set('AutoFormat.AutoParagraph', false);
            $config->set('AutoFormat.RemoveEmpty', true);
            $config->set('Output.TidyFormat', false);

            $cacheDir = storage_path('framework/cache/htmlpurifier');
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            $config->set('Cache.SerializerPath', $cacheDir);

            self::$instance = new \HTMLPurifier($config);
        }

        return self::$instance;
    }

    /** Purify a single HTML string. Returns null if input is null. */
    public static function clean(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        return self::make()->purify($html);
    }

    /** Purify multiple HTML fields in an array by key names. */
    public static function cleanFields(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = self::clean($data[$field]);
            }
        }
    }
}
