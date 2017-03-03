<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\Destination;
use Symfony\Component\HttpFoundation\Request;

final class RequestDestinationFactory
{
    public static function createFromRequest(Request $request, array $destination)
    {
        if (array_keys($destination) === range(0, count($destination) - 1)) {
            $destination = array_combine($destination, $destination);
        }

        $attrs = [];
        foreach ($destination as $seo => $attribute) {
            $attrs[$seo] = $request->attributes->get($attribute);
        }

        return new Destination($request->attributes->get('_route'), $attrs);
    }
}
