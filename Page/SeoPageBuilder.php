<?php

namespace Bankiru\Seo\Page;

class SeoPageBuilder extends SeoPage implements PageBuilder
{
    /** {@inheritdoc} */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /** {@inheritdoc} */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /** {@inheritdoc} */
    public function setCanonical($canonical = null)
    {
        $this->canonical = $canonical;

        return $this;
    }

    /** {@inheritdoc} */
    public function setHtmlAttributes(array $htmlAttributes)
    {
        $this->htmlAttributes = $htmlAttributes;

        return $this;
    }

    /** {@inheritdoc} */
    public function setBreadcrumbs(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    /** {@inheritdoc} */
    public function getSeoPage()
    {
        return $this;
    }
}
