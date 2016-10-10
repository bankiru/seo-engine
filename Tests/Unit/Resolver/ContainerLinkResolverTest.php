<?php

namespace Bankiru\Seo\Tests\Unit\Resolver;

use Bankiru\Seo\CompiledLink;
use Bankiru\Seo\Entity\ContainerLinkInterface;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Exception\LinkResolutionException;
use Bankiru\Seo\Integration\Local\TargetLink;
use Bankiru\Seo\Resolver\ContainerLinkResolver;
use Bankiru\Seo\Resolver\StaticLinkResolver;

class ContainerLinkResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testContainerResolver()
    {
        $cResolver = new ContainerLinkResolver();
        $cResolver->addResolver(new StaticLinkResolver());

        $dynLink = new TargetLink(new TargetDefinition('/route'), 'Title', []);
        self::assertFalse($cResolver->supports($dynLink));


        $statLink = new CompiledLink('/route', 'Title');
        self::assertFalse($cResolver->supports($statLink));
        try {
            $cResolver->resolve($statLink);
            self::fail('Should not resolve static links');
        } catch (LinkResolutionException $exception) {

        }

        $containerLink = $this->prophesize(ContainerLinkInterface::class);
        $containerLink->rewind()->willReturn();
        $containerLink->next()->willReturn();
        $containerLink->valid()->willReturn(true, false);
        $containerLink->current()->willReturn($statLink)->shouldBeCalled();

        $cLink = $containerLink->reveal();
        self::assertTrue($cResolver->supports($cLink));
        self::assertEquals([$statLink], $cResolver->resolve($cLink));

        $containerLink = $this->prophesize(ContainerLinkInterface::class);
        $containerLink->rewind()->willReturn();
        $containerLink->next()->willReturn();
        $containerLink->valid()->willReturn(true, true, false);

        $containerLink->current()->willReturn($statLink, $dynLink)->shouldBeCalled();

        $cLink = $containerLink->reveal();
        self::assertTrue($cResolver->supports($cLink));
        try {
            self::assertEquals([$statLink], $cResolver->resolve($cLink));
            self::fail('Should not resolve dynamic links');
        } catch (LinkResolutionException $exception) {
        }
    }
}
