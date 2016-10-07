<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Exception\PageException;
use Bankiru\Seo\Exception\ProcessingException;

final class Processor implements ProcessorInterface
{
    /** @var TargetRepositoryInterface */
    private $targetRepository;
    /** @var PageRepositoryInterface */
    private $pageRepository;

    public function __construct(TargetRepositoryInterface $targetRepository, PageRepositoryInterface $pageRepository)
    {
        $this->targetRepository = $targetRepository;
        $this->pageRepository   = $pageRepository;
    }

    /** {@inheritdoc} */
    public function process(DestinationInterface $destination)
    {
        $target = $this->getTarget($destination);
        if (null === $target) {
            throw ProcessingException::targetNotFound();
        }

        try {
            return $this->pageRepository->getByTargetDestination($target, $destination);
        } catch (PageException $exception) {
            throw new ProcessingException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param DestinationInterface $destination
     *
     * @return TargetDefinitionInterface|null
     */
    private function getTarget(DestinationInterface $destination)
    {
        $result = null;
        $i      = -100;
        foreach ($this->targetRepository->findByRoute($destination->getRoute()) as $target) {
            $score = $target->match($destination);
            if ($score > $i) {
                $result = $target;
                $i      = $score;
            }
        }

        return $result;
    }
}
