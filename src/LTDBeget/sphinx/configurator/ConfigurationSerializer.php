<?php

declare(strict_types=1);

namespace LTDBeget\sphinx\configurator;

use LTDBeget\sphinx\configurator\serializers\ArraySerializer;
use LTDBeget\sphinx\configurator\serializers\JsonSerializer;
use LTDBeget\sphinx\configurator\serializers\PlainSerializer;

class ConfigurationSerializer
{
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string
     * @throws \LTDBeget\sphinx\configurator\exceptions\ConfigurationException
     */
    public function toString(): string
    {
        return PlainSerializer::serialize($this->configuration);
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
        return JsonSerializer::serialize($this->configuration);
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
        return ArraySerializer::serialize($this->configuration);
    }
}
