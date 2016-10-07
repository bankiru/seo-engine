<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\Destination;
use Symfony\Component\HttpFoundation\Request;

final class RequestDestinationFactory
{
    public static function createFromRequest(Request $request)
    {
        return new Destination(
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params')
        );
    }
}
