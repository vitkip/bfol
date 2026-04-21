<?php

namespace App\Traits;

trait HasTranslations
{
    /**
     * Return the translated value of a field for the current locale,
     * falling back to Lao (_lo) if the locale variant is empty.
     */
    public function trans(string $field): string
    {
        $lang  = app()->getLocale();
        $value = $this->{"{$field}_{$lang}"} ?? null;

        return $value ?: ($this->{"{$field}_lo"} ?? '');
    }
}
