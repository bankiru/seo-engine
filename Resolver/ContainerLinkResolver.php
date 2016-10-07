<?php

namespace Bankiru\Seo\Resolver;

use Bankiru\Seo\Entity\ContainerLinkInterface;
use Bankiru\Seo\Exception\LinkGeneratorException;
use Bankiru\Seo\Exception\LinkResolutionException;

final class ContainerLinkResolver implements LinkResolver
{
    /** @var LinkResolver[] */
    private $resolvers = [];

    /**
     * ContainerLinkResolver constructor.
     *
     * @param LinkResolver[] $resolvers
     */
    public function __construct(array $resolvers = [])
    {
        $this->resolvers = $resolvers;
        $this->resolvers[] = $this;
    }

    public function addResolver(LinkResolver $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    /** {@inheritdoc} */
    public function resolve($container)
    {
        if (!$this->supports($container)) {
            throw new LinkResolutionException('Link type not supported');
        }

        /** @var ContainerLinkInterface $container */
        $result = [];
        foreach ($container as $link) {
            $result = array_merge($result, $this->matchResolver($link)->resolve($link));
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function supports($link)
    {
        return $link instanceof ContainerLinkInterface;
    }

    /**
     * @param $link
     *
     * @return LinkResolver
     * @throws LinkGeneratorException
     */
    private function matchResolver($link)
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports($link)) {
                return $resolver;
            }
        }
        throw new LinkGeneratorException('Cannot find link resolver for link: ' . get_class($link));
    }
}
