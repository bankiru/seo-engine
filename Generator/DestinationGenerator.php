<?php

namespace Bankiru\Seo\Generator;

use Bankiru\Seo\BankiruSeoBridge\SeoFilterFactoryInterface;
use Bankiru\Seo\Destination;
use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\SourceInterface;
use Bankiru\Seo\SourceFiller;

final class DestinationGenerator
{
    /**
     * @param string            $route
     * @param SourceInterface[] $sources
     * @param SourceFiller[]    $fillers
     *
     * @return DestinationInterface[]
     * @throws DestinationException
     */
    public function generate($route, array $sources, array $fillers = [])
    {
        $itemsCollection = $this->cartesian($sources);

        $this->validateFillerCircularity($fillers);

        $itemsCollection = $this->infer($itemsCollection, $fillers);

        return array_map(
            function ($items) use ($route) {
                return new Destination($route, $items);
            },
            $itemsCollection
        );
    }

    /**
     * @param \Countable[] $sources
     *
     * @return int|null
     */
    public function count(array $sources)
    {
        if (count($sources) === 0) {
            return 0;
        }

        $count = 1;
        foreach ($sources as $source) {
            $count *= $source->count();
        }

        return $count;
    }

    /**
     * Returns cartesian product of sources
     *
     * @param SourceInterface[] $sources
     *
     * @return array
     */
    private function cartesian($sources)
    {
        $result = [[]];

        foreach ($sources as $key => $source) {
            $append = [];

            foreach ($result as $product) {
                foreach ($source as $item) {
                    $product[$key] = $item;
                    $append[]      = $product;
                }
            }

            $result = $append;
        }

        return $result;
    }

    /**
     * @param SourceFiller[] $fillers
     *
     * @throws \RuntimeException
     */
    private function validateFillerCircularity(array $fillers)
    {
        foreach ($fillers as $code => $filler) {
            $visited = [$code];
            $this->visitFiller($filler, $fillers, $visited);
        }
    }

    /**
     * @param SourceFiller                $filler
     * @param SeoFilterFactoryInterface[] $fillers
     * @param string[]                    $visited
     *
     * @throws DestinationException
     */
    private function visitFiller(SourceFiller $filler, array $fillers, array &$visited)
    {
        foreach ($filler->getDependencies() as $code) {
            if (!array_key_exists($code, $fillers)) {
                continue;
            }

            if (in_array($code, $visited, true)) {
                throw DestinationException::circularInference($code, $visited);
            }

            $visited[] = $code;
            $this->visitFiller($fillers[$code], $fillers, $visited);
        }
    }

    /**
     * @param array[]                     $items
     * @param SeoFilterFactoryInterface[] $fillers
     *
     * @return array
     * @throws DestinationException
     */
    private function infer(array $items, array $fillers)
    {
        foreach ($items as &$item) {
            foreach ($fillers as $code => $filler) {
                $item = $this->inferCode($item, $code, $fillers);
            }
        }

        return $items;
    }

    /**
     * @param array          $item
     * @param string         $code
     * @param SourceFiller[] $fillers
     *
     * @return array
     *
     * @throws DestinationException
     */
    private function inferCode(array $item, $code, array $fillers)
    {
        if (!array_key_exists($code, $fillers)) {
            DestinationException::inferenceFailed($code, $item, $fillers);
        }

        $filler = $fillers[$code];
        foreach ($filler->getDependencies() as $key) {
            if (!array_key_exists($key, $item)) {
                $item[$key] = $this->inferCode($item, $key, $fillers);
            }
        }
        $item[$code] = $filler->infer($item);

        return $item;
    }
}
