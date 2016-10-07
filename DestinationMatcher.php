<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Exception\PageException;
use Bankiru\Seo\Target\TargetSorter;

final class DestinationMatcher implements DestinationMatcherInterface
{
    /** @var PageRepositoryInterface */
    private $pageRepository;
    /** @var TargetSorter */
    private $sorter;

    /**
     * DestinationMatcher constructor.
     *
     * @param TargetSorter            $sorter
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(TargetSorter $sorter, PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->sorter         = $sorter;
    }

    /** {@inheritdoc} */
    public function match(DestinationInterface $destination)
    {
        $target = $this->sorter->getMatchingTarget($destination);

        try {
            return $this->pageRepository->getByTargetDestination($target, $destination);
        } catch (PageException $exception) {
            throw new MatchingException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
