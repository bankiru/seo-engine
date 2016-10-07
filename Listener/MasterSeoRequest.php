<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\DestinationMatcherInterface;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Page\SeoPageInterface;

final class MasterSeoRequest
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


    /**
     * @return DestinationInterface|null
     * @throws \LogicException
     */
    public function getDestination()
    {
        if (null === $this->destination) {
            throw new \LogicException('Destination is not set');
        }

        return $this->destination;
    }

    /**
     * @param DestinationInterface $destination
     */
    public function setDestination(DestinationInterface $destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return SeoPageInterface
     * @throws \LogicException
     * @throws MatchingException
     */
    public function getPage()
    {
        if (null === $this->page) {
            $this->page = $this->matcher->match($this->getDestination());
        }

        return $this->page;
    }
}
