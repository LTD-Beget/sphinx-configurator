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
use LTDBeget\sphinx\configurator\serializers\ArraySerializer;
use LTDBeget\sphinx\configurator\serializers\JsonSerializer;
use LTDBeget\sphinx\configurator\serializers\PlainSerializer;
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

    public function addSource(Source $source)
    {
        // todo: check name uniqueness
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

    /**
     * @param string      $name
     * @param string|null $inheritanceName
     *
     * @return Index
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public function createIndex(string $name, string $inheritanceName = null): Index
    {
        $indexDefinition = new Index($this, $name, $inheritanceName);
        $this->indexes[] = $indexDefinition;

        return $indexDefinition;
    }

    /**
     * @return Index[]
     */
    public function iterateIndex()
    {
        foreach ($this->indexes as $index) {
            if (!$index->isDeleted()) {
                yield $index;
            }
        }
    }

    /**
     * @return Indexer
     */
    public function getIndexer(): Indexer
    {
        if (!$this->isHasIndexer()) {
            $this->initIndexer();
        }

        return $this->indexer;
    }

    /**
     * @return bool
     */
    public function isHasIndexer(): bool
    {
        return null !== $this->indexer && !$this->indexer->isDeleted();
    }

    /**
     * @internal
     * @return Configuration
     */
    private function initIndexer(): self
    {
        $this->indexer = new Indexer($this);

        return $this;
    }

    /**
     * @return Searchd
     */
    public function getSearchd(): Searchd
    {
        if (!$this->isHasSearchd()) {
            $this->initSearchd();
        }

        return $this->searchd;
    }

    /**
     * @return bool
     */
    public function isHasSearchd(): bool
    {
        return null !== $this->searchd && !$this->searchd->isDeleted();
    }

    /**
     * @internal
     * @return Configuration
     */
    private function initSearchd(): self
    {
        $this->searchd = new Searchd($this);

        return $this;
    }

    /**
     * @return Common
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function getCommon(): Common
    {
        $section = eSection::COMMON();
        if (!$this->isAllowedSection($section)) {
            throw new ConfigurationException("Sphinx of version {$this->version} does't have section {$section}");
        }

        if (!$this->isHasCommon()) {
            $this->initCommon();
        }

        return $this->common;
    }

    /**
     * @param eSection $section
     *
     * @return bool
     */
    public function isAllowedSection(eSection $section): bool
    {
        return $this->informer->isSectionExist($section);
    }

    /**
     * @return bool
     */
    public function isHasCommon(): bool
    {
        return null !== $this->common && !$this->common->isDeleted();
    }

    /**
     * @internal
     * @return Configuration
     */
    private function initCommon(): self
    {
        $this->common = new Common($this);

        return $this;
    }

    /**
     * @return Informer
     */
    public function getInformer(): Informer
    {
        return $this->informer;
    }

    /**
     * @return eVersion
     */
    public function getVersion(): eVersion
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            $string = PlainSerializer::serialize($this);
        } catch (\Exception $e) {
            $string = '';
        }

        return $string;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function toArray(): array
    {
        return ArraySerializer::serialize($this);
    }

    /**
     * @return string
     * @throws \LogicException
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function toJson(): string
    {
        return JsonSerializer::serialize($this);
    }
}
