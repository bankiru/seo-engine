<?php

namespace Bankiru\Seo\Tests\Unit;

use Bankiru\Seo\Context\ContextExtractor;
use Bankiru\Seo\Context\ContextItemInterface;
use Bankiru\Seo\Destination;

class ContextExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractor()
    {
        $extractor = new ContextExtractor();

        $contextItem = $this->prophesize(ContextItemInterface::class);
        $contextItem->getContext()->willReturn(['name' => 'item', 'subtitle' => 'title']);

        $destination = new Destination(
            'test',
            [
                'arg'     => '1',
                'context' => $contextItem->reveal(),
            ]
        );

        self::assertEquals(
            [
                'arg'     => '1',
                'context' => [
                    'name'     => 'item',
                    'subtitle' => 'title',
                ],
            ],
            $extractor->extractContext($destination)
        );
    }
}
