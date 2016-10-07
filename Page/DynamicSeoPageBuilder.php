<?php

namespace Bankiru\Seo\Page;

class DynamicSeoPageBuilder
{
    /** @var  array */
    protected $context;
    /** @var \Twig_Environment */
    protected $twig;

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;

        return $this;
    }
}
