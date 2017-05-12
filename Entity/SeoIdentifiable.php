<?php

namespace Bankiru\Seo\Entity;

interface SeoIdentifiable
{
    /**
     * Returns object identifier used for SEO matching
     *
     * @return mixed|array
     */
    public function getSeoIdentifier();

    /**
     * Returns SEO object alias for given entity
     *
     * @return string
     */
    public static function getSeoAlias();
}
