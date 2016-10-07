<?php

namespace Bankiru\Seo\Exception;

class MatchingException extends \RuntimeException implements SeoExceptionInterface
{
    public static function noTarget()
    {
        return new static('Destination does not match any target');
    }
}
