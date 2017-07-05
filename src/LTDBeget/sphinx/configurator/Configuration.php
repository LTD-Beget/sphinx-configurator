<?php
/**
 * @author: Viskov Sergey
 * @date  : 3/2/16
 * @time  : 4:33 PM
 */

namespace LTDBeget\sphinx\configurator;

use LTDBeget\sphinx\configurator\configurationEntities\sections\Common;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Index;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Indexer;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Searchd;
use LTDBeget\sphinx\configurator\configurationEntities\sections\Source;
use LTDBeget\sphinx\configurator\exceptions\ConfigurationException;
use LTDBeget\sphinx\enums\eSection;
use LTDBeget\sphinx\enums\eVersion;
use LTDBeget\sphinx\informer\Informer;

/**
 * Class Configuration
 *
 * @package LTDBeget\sphinx\configurator
 */
class Configuration
{
    /**
     * @var eVersion
     */
    private $version;
    /**
     * @var Informer
     */
    private $informer;
    /**
     * @var Source[]
     */
    private $sources = [];
    /**
     * @var Index[]
     */
    private $indexes = [];
    /**
     * @var Indexer
     */
    private $indexer;
    /**
     * @var Searchd
     */
    private $searchd;
    /**
     * @var Common
     */
    private $common;

    /**
     * Configuration constructor.
     *
     * @param eVersion $version
     *
     * @throws \LTDBeget\sphinx\informer\exceptions\DocumentationSourceException
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function __construct(eVersion $version)
    {
        // todo: version is not needed, Informer should be passed
        $this->version = $version;
        $this->informer = Informer::get($this->version);
    }

    public function getSearchd()
    {
        if (!$this->hasSearchd()) {
            return null;
        }

        return $this->searchd;
    }

    public function setSearchd(Searchd $searchd)
    {
        // todo: clone?
        $this->searchd = $searchd;
    }

    public function hasSearchd(): bool
    {
        return null !== $this->searchd && !$this->searchd->isDeleted();
    }

    public function addSource(Source $source)
    {
        // todo: check source name uniqueness
        // todo: clone?
        $this->sources[] = $source;
    }

    /**
     * @return Source[]
     */
    public function iterateSources()
    {
        // todo: replace with conditional iterator
        foreach ($this->sources as $source) {
            if (!$source->isDeleted()) {
                yield $source;
            }
        }
    }

    public function addIndex(Index $index)
    {
        // todo: check index name uniqueness
        // todo: clone?
        $this->indexes[] = $index;
    }

    /**
     * @return Index[]
     */
    public function iterateIndexes()
    {
        // todo: replace with conditional iterator
        foreach ($this->indexes as $index) {
            if (!$index->isDeleted()) {
                yield $index;
            }
        }
    }

    public function getIndexer()
    {
        if (!$this->hasIndexer()) {
            return null;
        }

        return $this->indexer;
    }

    public function setIndexer(Indexer $indexer)
    {
        // todo: clone?
        $this->indexer = $indexer;
    }

    public function hasIndexer(): bool
    {
        return null !== $this->indexer && !$this->indexer->isDeleted();
    }

    public function getCommon()
    {
        if (!$this->hasCommon()) {
            return null;
        }

        return $this->common;
    }

    /**
     * @param \LTDBeget\sphinx\configurator\configurationEntities\sections\Common $common
     *
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function setCommon(Common $common)
    {
        $section = eSection::COMMON();
        if (!$this->isAllowedSection($section)) {
            $version = $this->getVersion();
            throw new ConfigurationException("Sphinx of version {$version} does't have section {$section}");
        }

        // todo: clone?
        $this->common = $common;
    }

    public function hasCommon(): bool
    {
        return null !== $this->common && !$this->common->isDeleted();
    }

    public function isAllowedSection(eSection $section): bool
    {
        return $this->informer->isSectionExist($section);
    }

    public function getVersion(): eVersion
    {
        return $this->version;
    }

    public function getInformer(): Informer
    {
        return $this->informer;
    }
}
