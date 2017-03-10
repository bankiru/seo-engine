<?php

namespace Bankiru\Seo\Entity;

final class PermissiveCondition extends AbstractCondition
{
    /** {@inheritdoc} */
    protected function supports($object)
    {
        return true;
    }

    /** {@inheritdoc} */
    protected function doMatch($object)
    {
        return 0;
    }
}
