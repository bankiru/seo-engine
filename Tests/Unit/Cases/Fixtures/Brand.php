<?php

namespace Bankiru\Seo\Tests\Unit\Cases\Fixtures;

final class Brand
{
    /** @var string */
    public $name;

    /**
     * Brand constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
