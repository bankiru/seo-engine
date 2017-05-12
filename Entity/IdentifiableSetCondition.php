<?php

namespace Bankiru\Seo\Entity;

abstract class IdentifiableSetCondition extends AbstractCondition
{
    const MODE_EXCLUDE = 'exclude';
    const MODE_INCLUDE = 'include';

    /** @var string */
    private $alias;
    /** @var string */
    private $mode = self::MODE_EXCLUDE;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode === self::MODE_EXCLUDE ? self::MODE_EXCLUDE : self::MODE_INCLUDE;
    }

    /**
     * @return array
     */
    abstract public function getIdentifiers();

    /**
     * @return bool
     */
    public function isExclusive()
    {
        return $this->mode === self::MODE_EXCLUDE;
    }

    /**
     * @return bool
     */
    public function isInclusive()
    {
        return $this->mode === self::MODE_INCLUDE;
    }

    /**
     * @return int
     */
    abstract protected function getWeight();

    /**
     * Checks whether the object is valid argument to perform matching
     *
     * @param mixed $object
     *
     * @return bool
     */
    protected function supports($object)
    {
        return $object instanceof SeoIdentifiable && $object->getSeoAlias() === $this->alias;
    }

    /**
     * Verify that object matches according to condition configuration
     *
     * @param SeoIdentifiable $object
     *
     * @return int|null
     */
    protected function doMatch($object)
    {
        foreach ($this->getIdentifiers() as $id) {
            if ($object->getSeoIdentifier() === $id) {
                return $this->isExclusive() ? null : $this->getWeight();
            }
        }

        return $this->isExclusive() ? 0 : null;
    }
}
