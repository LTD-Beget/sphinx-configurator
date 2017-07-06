<?php

use LTDBeget\sphinx\configurator\ConfigurationFactory;
use LTDBeget\sphinx\configurator\ConfigurationHelper;
use LTDBeget\sphinx\enums\eSection;
use LTDBeget\sphinx\enums\eVersion;
use LTDBeget\sphinx\enums\options\eCommonOption;
use LTDBeget\sphinx\informer\Informer;

/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 16:01
 */
class InformerTest extends PHPUnit_Framework_TestCase
{
    public function testAllOptionInfo()
    {
        foreach( eVersion::getConstants() as $version) {
            $version = eVersion::get($version);
            $informer = Informer::get($version);
            foreach(eSection::getConstants() as $section) {
                $section = eSection::get($section);
                if($informer->isSectionExist($section)) {
                    foreach($informer->iterateOptionInfo($section) as $optionInfo) {
                        $optionInfo->getSection();
                        $optionInfo->getName();
                        $optionInfo->getDescription();
                        $optionInfo->getDocLink();
                        $optionInfo->getVersion();
                        $optionInfo->isIsMultiValue();
                    }
                }

            }
        }
    }

    /**
     * @expectedException \LTDBeget\sphinx\informer\exceptions\InformerRuntimeException
     * @expectedExceptionMessage Sphinx of version 2.1.9 does't have section common
     */
    public function testUnknownSection()
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $informer = Informer::get(eVersion::V_2_1_9());
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $informer->getOptionInfo(eSection::COMMON(), eCommonOption::JSON_AUTOCONV_KEYNAMES());
    }

    public function testGetInfoFromConfiguration()
    {
        $config_path = __DIR__. '/../sphinx/conf/valid.example.conf';
        $plain_config = file_get_contents($config_path);
        $config = ConfigurationFactory::fromString($plain_config, eVersion::V_2_2_10());
        foreach($config->iterateIndexes() as $section) {
            foreach($section->iterateOptions() as $option) {
                $option->getInfo();
            }
        }
        foreach($config->iterateSources() as $section) {
            foreach($section->iterateOptions() as $option) {
                $option->getInfo();
            }
        }

        if($config->hasIndexer()) {
            foreach(ConfigurationHelper::getOrCreateIndexer($config)->iterateOptions() as $option) {
                $option->getInfo();
            }
        }

        if($config->hasSearchd()) {
            foreach(ConfigurationHelper::getOrCreateSearchd($config)->iterateOptions() as $option) {
                $option->getInfo();
            }
        }

        if($config->hasCommon()) {
            foreach(ConfigurationHelper::getOrCreateCommon($config)->iterateOptions() as $option) {
                $option->getInfo();
            }
        }
    }
}
