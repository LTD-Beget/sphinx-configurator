<?php

declare(strict_types=1);

namespace LTDBeget\sphinx\configurator;

use LTDBeget\sphinx\configurator\configurationEntities\sections\Common;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Index;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Indexer;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Searchd;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Source;
use LTDBeget\sphinx\configurator\exceptions\ConfigurationException;
use LTDBeget\sphinx\enums\eSection;

class ConfigurationHelper
{
    /**
     * @param Configuration $configuration
     *
     * @param string        $name
     * @param string|null   $inheritanceName
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Source
     * @throws \LogicException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \InvalidArgumentException
     */
    public static function createSource(
        Configuration $configuration,
        string $name,
        string $inheritanceName = null
    ): Source {
        $source = new Source($configuration, $name, $inheritanceName);
        $configuration->addSource($source);

        return $source;
    }

    /**
     * @param Configuration $configuration
     *
     * @param string        $name
     * @param string|null   $inheritanceName
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Index
     * @throws \LogicException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \InvalidArgumentException
     */
    public static function createIndex(Configuration $configuration, string $name, string $inheritanceName = null
    ): Index {
        $indexDefinition = new Index($configuration, $name, $inheritanceName);
        $configuration->addIndex($indexDefinition);

        return $indexDefinition;
    }

    /**
     * @param Configuration $configuration
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Indexer
     */
    public static function getOrCreateIndexer($configuration): Indexer
    {
        if (!$configuration->hasIndexer()) {
            $configuration->setIndexer(new Indexer($configuration));
        }

        return $configuration->getIndexer();
    }

    /**
     * @param Configuration $configuration
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Searchd
     */
    public static function getOrCreateSearchd($configuration): Searchd
    {
        if (!$configuration->hasSearchd()) {
            $configuration->setSearchd(new Searchd($configuration));
        }

        return $configuration->getSearchd();
    }

    /**
     * @param Configuration $configuration
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Common
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public static function getOrCreateCommon($configuration): Common
    {
        $section = eSection::COMMON();
        if (!$configuration->isAllowedSection($section)) {
            $version = $configuration->getVersion();
            throw new ConfigurationException("Sphinx of version {$version} does't have section {$section}");
        }

        if (!$configuration->hasCommon()) {
            $configuration->setCommon(new Common($configuration));
        }

        return $configuration->getCommon();
    }
}
