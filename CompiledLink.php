<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Entity\LinkInterface;

final class CompiledLink implements LinkInterface
{
    /** @var  string */
    private $href;

    /** @var string|null */
    private $title;

    /** @var string[] */
    private $attributes = [];

    /**
     * CompiledLink constructor.
     *
     * @param string    $href
     * @param string    $title
     * @param string[] $attributes
     */
    public function __construct($href, $title = null, array $attributes = [])
    {
        $this->href       = $href;
        $this->title      = $title;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
