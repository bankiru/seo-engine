<?php

namespace Bankiru\Seo;

final class Processor implements ProcessorInterface
{
    /** @var TargetRepositoryInterface */
    private $repository;

    public function __construct(TargetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /** {@inheritdoc} */
    public function process(DestinationInterface $destination)
    {
        $target = $this->getTarget($destination);
        if (null === $target) {
            return null;
        }

        return $target->getSeoPage();
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
        foreach ($this->repository->findByRoute($destination->getRoute()) as $target) {
            $score = $target->match($destination);
            if ($score > $i) {
                $result = $target;
                $i      = $score;
            }
        }

        return $result;
    }
}
