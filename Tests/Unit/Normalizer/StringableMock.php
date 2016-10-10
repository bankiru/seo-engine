<?php

namespace Bankiru\Seo\Tests\Unit\Normalizer;

class StringableMock
{
    /** @var string */
    private $string;

    /**
     * Stringable constructor.
     *
     * @param $string
     */
    public function __construct($string)
    {
        $this->string = (string)$string;
    }

    public function __toString()
    {
        return $this->string;
    }
}

