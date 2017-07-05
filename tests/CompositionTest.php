<?php

declare(strict_types=1);

use LTDBeget\sphinx\configurator\Configuration;
use LTDBeget\sphinx\enums\eVersion;
use LTDBeget\sphinx\enums\options\eCommonOption;
use LTDBeget\sphinx\enums\options\eIndexerOption;
use LTDBeget\sphinx\enums\options\eIndexOption;
use LTDBeget\sphinx\enums\options\eSearchdOption;
use LTDBeget\sphinx\enums\options\eSourceOption;

class CompositionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_support_composition_from_classes()
    {
        $version = eVersion::V_2_2_10();
        $sut = new Configuration($version);

        $sut->setCommon(new Common($sut));
        $sut->setSearchd(new Searchd($sut));
        $sut->setIndexer(new Indexer($sut));
        $sut->addSource(new Source($sut));
        $sut->addIndex(new Index($sut));
    }
}

class Searchd extends \LTDBeget\sphinx\configurator\configurationEntities\sections\Searchd
{
    public function __construct(\LTDBeget\sphinx\configurator\Configuration $configuration)
    {
        parent::__construct($configuration);
        $this->addOption(eSearchdOption::CLIENT_TIMEOUT(), '5');
    }
}

class Indexer extends \LTDBeget\sphinx\configurator\configurationEntities\sections\Indexer
{
    public function __construct(\LTDBeget\sphinx\configurator\Configuration $configuration)
    {
        parent::__construct($configuration);
        $this->addOption(eIndexerOption::MEM_LIMIT(), '256M');
    }
}

class Common extends \LTDBeget\sphinx\configurator\configurationEntities\sections\Common
{
    public function __construct(\LTDBeget\sphinx\configurator\Configuration $configuration)
    {
        parent::__construct($configuration);
        $this->addOption(eCommonOption::LEMMATIZER_BASE(), '/var/lib/sphinx/dict/');
    }
}

class Source extends \LTDBeget\sphinx\configurator\configurationEntities\sections\Source
{
    const NAME = 'test';

    public function __construct(\LTDBeget\sphinx\configurator\Configuration $configuration)
    {
        $inheritance = null;

        parent::__construct($configuration, self::NAME, $inheritance);
        $this->addOption(eSourceOption::SQL_QUERY(), 'select * from some_source');
    }
}

class Index extends \LTDBeget\sphinx\configurator\configurationEntities\sections\Index
{
    public function __construct(\LTDBeget\sphinx\configurator\Configuration $configuration)
    {
        $name = 'test';
        $inheritance = null;

        parent::__construct($configuration, $name, $inheritance);
        $this->addOption(eIndexOption::SOURCE(), Source::NAME);
    }
}
