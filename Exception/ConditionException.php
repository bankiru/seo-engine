<?php

namespace Bankiru\Seo\Exception;

use Bankiru\Seo\ConditionInterface;

class ConditionException extends \RuntimeException implements SeoExceptionInterface
{
    public static function unsupportedArgumentToMatch(ConditionInterface $condition, $argument)
    {
        return new static(
            sprintf(
                '%s cannot match argument of type %s',
                get_class($condition),
                is_object($argument) ? get_class($argument) : gettype($argument)
            )
        );
    }
}
