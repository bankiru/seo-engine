<?php

namespace Bankiru\Seo\Exception;

class ProcessingException extends \RuntimeException implements SeoExceptionInterface
{
    public static function targetNotFound()
    {
        return new static('Destination does not match any target');
    }
}
