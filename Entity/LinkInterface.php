<?php

namespace Bankiru\Seo\Entity;

interface LinkInterface
{
    /** @return string */
    public function getHref();

    /** @return string */
    public function getTitle();

    /** @return string[] */
    public function getAttributes();
}
