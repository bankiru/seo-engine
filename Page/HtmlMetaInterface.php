<?php

namespace Bankiru\Seo\Page;

interface HtmlMetaInterface
{
    /** @return string|null */
    public function getName();

    /** @return string */
    public function getContent();

    /** @return string[] */
    public function getAttributes();

    /** @return null|string */
    public function getHttpEquiv();
}
