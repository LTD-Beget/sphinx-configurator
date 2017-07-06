<?php

declare(strict_types=1);

namespace LTDBeget\sphinx\configurator;

use LTDBeget\sphinx\configurator\deserializers\ArrayDeserializer;
use LTDBeget\sphinx\configurator\deserializers\JsonDeserializer;
use LTDBeget\sphinx\configurator\deserializers\PlainDeserializer;
use LTDBeget\sphinx\enums\eVersion;

class ConfigurationFactory
{
    /**
     * @param string   $plainData
     * @param eVersion $version
     *
     * @return Configuration
     * @throws \Hoa\Ustring\Exception
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     * @throws \LTDBeget\sphinx\configurator\exceptions\DeserializeException
     * @throws \LTDBeget\sphinx\SyntaxErrorException
     * @throws \BadMethodCallException
     * @throws \LTDBeget\sphinx\informer\exceptions\DocumentationSourceException
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public static function fromString(string $plainData, eVersion $version): Configuration
    {
        return PlainDeserializer::deserialize($plainData, new Configuration($version));
    }

    /**
     * @param array    $plainData
     * @param eVersion $version
     *
     * @return Configuration
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     * @throws \LTDBeget\sphinx\configurator\exceptions\DeserializeException
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     * @throws \LTDBeget\sphinx\informer\exceptions\DocumentationSourceException
     */
    public static function fromArray(array $plainData, eVersion $version): Configuration
    {
        return ArrayDeserializer::deserialize($plainData, new Configuration($version));
    }

    /**
     * @param string   $plainData
     * @param eVersion $version
     *
     * @return Configuration
     * @throws \LTDBeget\sphinx\configurator\exceptions\SectionException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     * @throws \LTDBeget\sphinx\configurator\exceptions\DeserializeException
     * @throws \LTDBeget\sphinx\informer\exceptions\DocumentationSourceException
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public static function fromJson(string $plainData, eVersion $version): Configuration
    {
        return JsonDeserializer::deserialize($plainData, new Configuration($version));
    }
}
