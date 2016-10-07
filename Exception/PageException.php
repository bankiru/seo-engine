<?php

namespace Bankiru\Seo\Exception;

use Bankiru\Seo\TargetDefinitionInterface;

class PageException extends \RuntimeException implements SeoExceptionInterface
{
    public static function notFound(TargetDefinitionInterface $target)
    {
        return new static(
            sprintf(
                'No SEO page associated with target "%s"',
                method_exists($target, '__toString') ? (string)$target : $target->getRoute()
            )
        );
    }
}
