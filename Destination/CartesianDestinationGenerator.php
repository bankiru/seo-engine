<?php

namespace Bankiru\Seo\Destination;

use Bankiru\Seo\Destination;
use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\SourceFiller;
use Bankiru\Seo\SourceInterface;

final class CartesianDestinationGenerator implements DestinationGenerator
{
    /** {@inheritdoc} */
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

    /** {@inheritdoc} */
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
     * @throws DestinationException
     */
    private function validateFillerCircularity(array $fillers)
    {
        foreach ($fillers as $code => $filler) {
            $visited = [$code];
            $this->visitFiller($filler, $fillers, $visited);
        }
    }

    /**
     * @param SourceFiller   $filler
     * @param SourceFiller[] $fillers
     * @param string[]       $visited
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
     * @param array[]        $items
     * @param SourceFiller[] $fillers
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
