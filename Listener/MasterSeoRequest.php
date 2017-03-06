<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\DestinationMatcherInterface;
use Bankiru\Seo\Page\SeoPageInterface;

class MasterSeoRequest implements SeoRequestInterface
{
    /** @var  DestinationInterface */
    private $destination;
    /** @var  SeoPageInterface */
    private $page;
    /** @var  DestinationMatcherInterface */
    private $matcher;

    /**
     * MasterSeoRequest constructor.
     *
     * @param DestinationMatcherInterface $matcher
     */
    public function __construct(DestinationMatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    /** {@inheritdoc} */
    public function getDestination()
    {
        if (null === $this->destination) {
            throw new \LogicException('Destination is not set');
        }

        return $this->destination;
    }

    /** {@inheritdoc} */
    public function setDestination(DestinationInterface $destination)
    {
        $this->destination = $destination;
    }

    /** {@inheritdoc} */
    public function getPage()
    {
        if (null === $this->page) {
            $this->page = $this->matcher->match($this->getDestination());
        }

        return $this->page;
    }

    public function getRenderedPage()
    {
        return $this->getPage();
    }
}
