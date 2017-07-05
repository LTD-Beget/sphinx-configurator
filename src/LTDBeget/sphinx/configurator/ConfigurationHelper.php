<?php

declare(strict_types=1);

namespace LTDBeget\sphinx\configurator;

use LTDBeget\sphinx\configurator\configurationEntities\sections\Source;

class ConfigurationHelper
{
    /**
     * @param Configuration $configuration
     *
     * @param string        $name
     * @param string|null   $inheritanceName
     *
     * @return \LTDBeget\sphinx\configurator\configurationEntities\sections\Source
     */
    public static function createSource(Configuration $configuration, string $name, string $inheritanceName = null
    ): Source {
        $source = new Source($configuration, $name, $inheritanceName);
        $configuration->addSource($source);

        return $source;
    }
}
