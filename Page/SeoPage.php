<?php

namespace Bankiru\Seo\Page;

class SeoPage implements SeoPageInterface
{
    /** @var HtmlMetaInterface[] */
    protected $meta = [];
    /** @var string */
    protected $title;
    /** @var string */
    protected $canonical;
    /** @var string[] */
    protected $htmlAttributes = [];
    /** @var string[] */
    protected $breadcrumbs = [];

    /** @return HtmlMetaInterface[] */
    public function getMeta()
    {
        return $this->meta;
    }

    /** @return string */
    public function getTitle()
    {
        return $this->title;
    }

    /** @return string[] */
    public function getHtmlAttributes()
    {
        return $this->htmlAttributes;
    }

    /** @return string|null */
    public function getCanonicalLink()
    {
        return $this->canonical;
    }

    /** @return string[] */
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }
}
