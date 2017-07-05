<?php
/**
 * @author: Viskov Sergey
 * @date  : 3/3/16
 * @time  : 5:13 PM
 */

namespace LTDBeget\sphinx\configurator\serializers;

use LTDBeget\sphinx\configurator\Configuration;
use LTDBeget\sphinx\configurator\ConfigurationHelper;

/**
 * Class PlainSerializer
 * serialize Configuration object to string for file .conf
 *
 * @package LTDBeget\sphinx\configurator\serializers
 */
final class PlainSerializer
{
    /**
     * @var string
     */
    private $string = '';

    /**
     * @var Configuration
     */
    private $object;

    /**
     * @internal
     * ArrayDeserializer constructor.
     */
    private function __construct()
    {
    }

    /**
     * Make plain content for sphinx configuration file from Configuration object
     *
     * @param Configuration $configuration
     *
     * @return string
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public static function serialize(Configuration $configuration) : string
    {
        $serializer         = new self();
        $serializer->object = $configuration;

        return $serializer->serializeInternal();
    }

    /**
     * @internal
     * @return string
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function serializeInternal() : string
    {
        $this->serializeSources();
        $this->serializeIndexes();
        $this->serializeIndexer();
        $this->serializeSearchd();
        $this->serializeCommon();

        return $this->string;
    }

    /**
     * @internal
     */
    private function serializeSources()
    {
        foreach ($this->object->iterateSources() as $source) {
            $this->string .= (string) $source;
        }
    }

    /**
     * @internal
     */
    private function serializeIndexes()
    {
        foreach ($this->object->iterateIndexes() as $index) {
            $this->string .= (string) $index;
        }
    }

    /**
     * @internal
     */
    private function serializeIndexer()
    {
        if ($this->object->hasIndexer()) {
            $this->string .= (string)ConfigurationHelper::getOrCreateIndexer($this->object);
        }
    }

    /**
     * @internal
     */
    private function serializeSearchd()
    {
        if ($this->object->hasSearchd()) {
            $this->string .= (string)ConfigurationHelper::getOrCreateSearchd($this->object);
        }
    }

    /**
     * @internal
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    private function serializeCommon()
    {
        if ($this->object->hasCommon()) {
            $this->string .= (string)ConfigurationHelper::getOrCreateCommon($this->object);
        }
    }
}
