<?php

namespace Bankiru\Seo\Tests\Unit;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\Entity\AbstractCondition;
use Bankiru\Seo\Exception\ConditionException;

class AbstractConditionTest extends \PHPUnit_Framework_TestCase
{
    public function testConditionThrowsExceptionOnUnsupportedObject()
    {
        /** @var ConditionInterface|\PHPUnit_Framework_MockObject_MockObject $condition */
        $condition = $this
            ->getMockForAbstractClass(AbstractCondition::class);

        $condition->method('supports')->withAnyParameters()->willReturn(false);

        try {
            self::assertFalse($condition->match(new \stdClass()));
            self::fail('should not match');
        } catch (ConditionException $exception) {
        }
    }
}
