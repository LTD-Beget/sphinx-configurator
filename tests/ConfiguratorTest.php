<?php
/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 5:58
 */

use LTDBeget\sphinx\configurator\Configuration;
use LTDBeget\sphinx\configurator\ConfigurationFactory;
use LTDBeget\sphinx\configurator\ConfigurationHelper;
use LTDBeget\sphinx\enums\eVersion;
use LTDBeget\sphinx\enums\options\eIndexOption;

/**
 * Class ConfiguratorTest
 */

class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     * @expectedExceptionMessage Sphinx of version 2.1.8 does't have section common
     */
    public function testCheckConfigValidInNewerVersionAndInvalidInPrevious()
    {
        $config_path = __DIR__. '/../sphinx/conf/valid.example.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());

    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Inheritance with name SOmeDummyInheritance of section source doesn't exists in configuration
     */
    public function testNotFoundInheritanceSource()
    {
        $config_path = __DIR__ . '/../sphinx/conf/invalid/inheritance_source.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Inheritance with name some_dummy_inheritance of section index doesn't exists in configuration
     */
    public function testNotFoundInheritanceIndex()
    {
        $config_path = __DIR__ . '/../sphinx/conf/invalid/inheritance_index.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Duplicate name mainSource found in source section
     */
    public function testDuplicateNameSource()
    {
        $config_path = __DIR__ . '/../sphinx/conf/invalid/duplicate_source_name.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Duplicate name user_index found in index section
     */
    public function testDuplicateNameIndex()
    {
        $config_path = __DIR__ . '/../sphinx/conf/invalid/duplicate_index_name.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\DeserializeException
     * @expectedExceptionMessage Unknown option name group_id in section type source
     */
    public function testCommentHell()
    {
        $config_path = __DIR__ . '/../sphinx/conf/invalid/comments_hell.conf';
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationFactory::fromString($plain_config, eVersion::V_2_1_8());
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Name or inheritance of section source must contains only A-Za-z and _ symbols
     */
    public function testWrongName()
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationHelper::createSource(new Configuration(eVersion::V_2_2_10()), 'SOME WRONG NAME');
    }

    /**
     * @expectedException \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @expectedExceptionMessage Name or inheritance of section source must contains only A-Za-z and _ symbols
     */
    public function testWrongInheritance()
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        ConfigurationHelper::createSource(new Configuration(eVersion::V_2_2_10()), 'valid_name',
            'S o m&^ e shit');
    }

    /**
     * @expectedException \LTDBeget\sphinx\informer\exceptions\InformerRuntimeException
     * @expectedExceptionMessage For sphinx v. 2.2.10 option charset_type in index isn't available
     */
    public function testAddPermanentlyRemovedOption()
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $index = (new Configuration(eVersion::V_2_2_10()))->createIndex('valid_name');
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $index->addOption(eIndexOption::CHARSET_TYPE(), "utf-8");
    }

    public function testChainSerializeDeserialize()
    {
        $config_path = __DIR__. '/../sphinx/conf/valid.example.conf';
        $plain_config = file_get_contents($config_path);

        $referenceHash = md5((string)ConfigurationFactory::fromString($plain_config, eVersion::V_2_2_10()));


        $config = ConfigurationFactory::fromString($plain_config, eVersion::V_2_2_10());
        $config = ConfigurationFactory::fromArray($config->toArray(), eVersion::V_2_2_10());
        $config = ConfigurationFactory::fromJson($config->toJson(), eVersion::V_2_2_10());
        $config = ConfigurationFactory::fromString((string)$config, eVersion::V_2_2_10());

        $hash = md5((string) $config);

        static::assertEquals($referenceHash, $hash);
    }

    public function testDelete()
    {
        $config_path = __DIR__. '/../sphinx/conf/valid.example.conf';
        $plain_config = file_get_contents($config_path);

        $config = ConfigurationFactory::fromString($plain_config, eVersion::V_2_2_10());
        foreach($config->iterateIndex() as $section) {
            $section->delete();
        }
        foreach($config->iterateSources() as $section) {
            $section->delete();
        }

        if($config->isHasIndexer()) {
            $config->getIndexer()->delete();
        }

        if($config->isHasSearchd()) {
            $config->getSearchd()->delete();
        }

        if($config->isHasCommon()) {
            foreach($config->getCommon()->iterateOptions() as $option) {
                $option->delete();
            }
        }

        $hash = md5((string) $config);

        /** @noinspection SpellCheckingInspection */
        static::assertEquals('f26517544c25d8ef994622380a0afbe9', $hash);
    }

    public function testUnicode()
    {
        $config_path = __DIR__. '/../sphinx/conf/unicode.conf';
        $plain_config = file_get_contents($config_path);

        $config = ConfigurationFactory::fromString($plain_config, eVersion::V_2_2_10());

        $hash = md5((string) $config);

        /** @noinspection SpellCheckingInspection */
        static::assertEquals('2b841aab6bf02ea10f3fdec82eee0872', $hash);
    }

    public function testCheckGetInheritance()
    {
        $configuration = new Configuration(eVersion::V_2_2_10());

        $parent_name = 'source1';
        $child_name = 'source2';

        $parent = ConfigurationHelper::createSource($configuration, $parent_name);
        $child = ConfigurationHelper::createSource($configuration, $child_name, $parent_name);

        static::assertSame($child->getInheritance(), $parent);
    }


    public function testOnRemoveParentRemoveChild()
    {
        $configuration = new Configuration(eVersion::V_2_2_10());

        $parent_name = 'source1';
        $child_name = 'source2';

        $parent = ConfigurationHelper::createSource($configuration, $parent_name);
        $child = ConfigurationHelper::createSource($configuration, $child_name, $parent_name);

        $parent->delete();

        static::assertTrue($child->isDeleted());
    }
}
