<?php

namespace Bankiru\Seo\Page;

interface PageBuilder
{
    /**
     * @return SeoPageInterface
     */
    public function getSeoPage();

    /**
     * @param HtmlMetaInterface[] $meta
     *
     * @return $this
     */
    public function setMeta(array $meta);

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * @param string|null $canonical
     *
     * @return $this
     */
    public function setCanonical($canonical = null);

    /**
     * @param string[] $htmlAttributes
     *
     * @return $this
     */
    public function setHtmlAttributes(array $htmlAttributes);

    /**
     * @param string[] $breadcrumbs
     *
     * @return $this
     */
    public function setBreadcrumbs(array $breadcrumbs);
}
