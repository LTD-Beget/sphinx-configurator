<?php
/**
 * @author: Viskov Sergey
 * @date  : 3/14/16
 * @time  : 12:54 PM
 */

namespace LTDBeget\sphinx\configurator\serializers;

use LTDBeget\sphinx\configurator\Configuration;

/**
 * Class ArraySerializer
 * serialize Configuration object to array
 *
 * @package LTDBeget\sphinx\configurator\serializers
 */
class ArraySerializer
{
    /**
     * @var array
     */
    private $arrayConfiguration = [];
    /**
     * @var Configuration
     */
    private $objectConfiguration;

    /**
     * @internal
     * ArraySerializer constructor.
     */
    private function __construct()
    {
    }

    /**
     * Make array represent of Configuration object
     *
     * @param Configuration $configuration
     *
     * @return array
     * @throws \LogicException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public static function serialize(Configuration $configuration) : array
    {
        $serializer                      = new self();
        $serializer->objectConfiguration = $configuration;

        return $serializer->serializeInternal();
    }

    /**
     * @internal
     * @return array
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    private function serializeInternal() : array
    {
        $this->serializeSource();
        $this->serializeIndex();
        $this->serializeIndexer();
        $this->serializeSearchhd();
        $this->serializeCommon();

        return $this->arrayConfiguration;
    }

    /**
     * @internal
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     */
    private function serializeSource()
    {
        foreach ($this->objectConfiguration->iterateSources() as $source) {
            $this->arrayConfiguration[] = $source->toArray();
        }
    }

    /**
     * @internal
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     */
    private function serializeIndex()
    {
        foreach ($this->objectConfiguration->iterateIndex() as $index) {
            $this->arrayConfiguration[] = $index->toArray();
        }
    }

    /**
     * @internal
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function serializeIndexer()
    {
        if ($this->objectConfiguration->isHasIndexer()) {
            $this->arrayConfiguration[] = $this->objectConfiguration->getIndexer()->toArray();
        }
    }

    /**
     * @internal
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function serializeSearchhd()
    {
        if ($this->objectConfiguration->isHasSearchd()) {
            $this->arrayConfiguration[] = $this->objectConfiguration->getSearchd()->toArray();
        }
    }

    /**
     * @internal
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function serializeCommon()
    {
        if ($this->objectConfiguration->isHasCommon()) {
            $this->arrayConfiguration[] = $this->objectConfiguration->getCommon()->toArray();
        }
    }
}
