<?php

namespace App\Custom;

use Hashids\Hashids;

class Hasher
{
    public static function encode(...$args)
    {
        return app(Hashids::class)->encode(...$args);
    }

    public static function decode($enc)
    {
        // Decode the value.
        $decoded = app(Hashids::class)->decode($enc);

        // Return the first item if we were able to decode it.
        if (count($decoded)) {
            return $decoded[0];
        }

        return '';
    }
}
