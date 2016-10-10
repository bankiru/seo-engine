<?php

namespace Bankiru\Seo\Tests\Unit;

use Bankiru\Seo\Integration\Local\CallbackCondition;

class CallbackConditionTest extends \PHPUnit_Framework_TestCase
{
    public function testCallbackCondition()
    {
        $condition = new CallbackCondition('is_bool');
        self::assertNull($condition->match(1));
        self::assertNull($condition->match('1'));
        self::assertEquals(1, $condition->match(true));
        self::assertEquals(1, $condition->match(false));
    }
}
