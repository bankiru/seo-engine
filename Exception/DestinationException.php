<?php
namespace Bankiru\Seo\Exception;

class DestinationException extends \RuntimeException implements SeoExceptionInterface
{
    public static function normalizationFailed($item, $expected = null)
    {
        $message = sprintf(
            'Cannot get slug for item of type %s.',
            is_object($item) ? get_class($item) : gettype($item)
        );
        if ($expected) {
            $message .= sprintf(' Item of type %s expected.', $expected);
        }

        return new static($message);
    }

    public static function inferenceFailed($code, array $item, array $fillers)
    {
        return new static(
            sprintf(
                'Unable to infer item code %s from %s (known fillers are %s)',
                $code,
                implode(',', array_keys($item)),
                implode(',', array_keys($fillers))
            )
        );
    }

    public static function circularInference($code, $visited)
    {
        return new static(sprintf('Circular inference detected: %s <- %s', $code, implode(',', $visited)));
    }
}
