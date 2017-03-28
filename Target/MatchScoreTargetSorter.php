<?php

namespace Bankiru\Seo\Target;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\TargetRepositoryInterface;

final class MatchScoreTargetSorter implements TargetSorter
{
    /** @var TargetRepositoryInterface */
    private $targetRepository;

    /**
     * MatchScoreTargetSorter constructor.
     *
     * @param TargetRepositoryInterface $targetRepository
     */
    public function __construct(TargetRepositoryInterface $targetRepository)
    {
        $this->targetRepository = $targetRepository;
    }

    /** {@inheritdoc} */
    public function getMatchingTarget(DestinationInterface $destination)
    {
        $sorted = [];
        foreach ($this->targetRepository->findByRoute($destination->getRoute()) as $target) {
            $score = $target->match($destination);
            if (null !== $score) {
                $sorted[$score] = $target;
            }
        }

        if (count($sorted) === 0) {
            throw MatchingException::noTarget();
        }

        krsort($sorted);

        return array_shift($sorted);
    }
}
