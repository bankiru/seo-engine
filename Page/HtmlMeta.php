<?php

namespace Bankiru\Seo\Page;

final class HtmlMeta implements HtmlMetaInterface
{
    private $name;

    private $content;

    private $attributes = [];

    private $httpEquiv;

    /**
     * HtmlMeta constructor.
     *
     * @param       $content
     * @param       $name
     * @param       $httpEquiv
     * @param array $attributes
     */
    private function __construct($content, $name = null, $httpEquiv = null, array $attributes = [])
    {
        $this->name       = $name;
        $this->content    = $content;
        $this->attributes = $attributes;
        $this->httpEquiv  = $httpEquiv;
    }

    public static function createHttpEquivMeta($header, $content, array $attributes = [])
    {
        return new static($content, null, $header, $attributes);
    }

    public static function createNameMeta($name, $content, array $attributes = [])
    {
        return new static($content, $name, null, $attributes);
    }

    /** @return string|null */
    public function getName()
    {
        return $this->name;
    }

    /** @return string */
    public function getContent()
    {
        return $this->content;
    }

    /** @return string[] */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /** @return null|string */
    public function getHttpEquiv()
    {
        return $this->httpEquiv;
    }
}
