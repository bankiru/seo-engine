<?php

namespace Bankiru\Seo\Page;

interface SeoPageInterface
{
    /** @return HtmlMetaInterface[] */
    public function getMeta();

    /** @return string */
    public function getTitle();

    /** @return string[] */
    public function getHtmlAttributes();

    /** @return string|null */
    public function getCanonicalLink();

    /** @return string[] */
    public function getBreadcrumbs();
}
