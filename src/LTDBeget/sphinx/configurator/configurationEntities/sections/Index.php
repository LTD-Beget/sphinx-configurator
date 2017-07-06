<?php
/**
 * @author: Viskov Sergey
 * @date  : 3/18/16
 * @time  : 5:32 PM
 */

namespace LTDBeget\sphinx\configurator\configurationEntities\sections;

use LTDBeget\sphinx\configurator\configurationEntities\base\Definition;
use LTDBeget\sphinx\configurator\configurationEntities\Option;
use LTDBeget\sphinx\enums\options\eIndexOption;

/**
 * Class Index
 *
 * @package LTDBeget\sphinx\configurator\configurationEntities\base\sections
 */
class Index extends Definition
{
    /**
     * @param eIndexOption $name
     * @param string       $value
     *
     * @return Option
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \LTDBeget\sphinx\informer\exceptions\InformerRuntimeException
     */
    public function addOption(eIndexOption $name, string $value) : Option
    {
        return $this->addOptionInternal($name, $value);
    }

    /**
     * @return Source
     * @throws \RuntimeException
     */
    public function findSource() : Source
    {
        $source_name = NULL;
        $index       = $this;

        do {
            foreach ($index->iterateOptions() as $option) {
                if(eIndexOption::SOURCE()->is($option->getName()) ) {
                    $source_name = $option->getValue();
                }
            }
            if($index->isHasInheritance()) {
                $index = $index->getInheritance();
            }
        } while($index->isHasInheritance());

        foreach ($this->getConfiguration()->iterateSources() as $source) {
            if($source->getName() === $source_name) {
                return $source;
            }
        }

        throw new \RuntimeException('Source for index does not found');
    }

    /**
     * @return bool
     */
    public function isHasSource() : bool
    {
        $isHasSource = false;
        $index       = $this;

        do {
            foreach ($index->iterateOptions() as $option) {
                if (eIndexOption::SOURCE()->is($option->getName())) {
                    $isHasSource = true;
                }
            }
            if ($index->isHasInheritance()) {
                $index = $index->getInheritance();
            }
        } while ($index->isHasInheritance());

        return $isHasSource;
    }

    /**
     * @return bool
     */
    public function isDistributed() : bool
    {
        $indexWithoutSource = false;
        foreach ($this->iterateOptions() as $option) {
            if ($option->getName()->is(eIndexOption::TYPE()) && $option->getValue() === 'distributed') {
                $indexWithoutSource = true;
            }
        }

        return $indexWithoutSource;
    }
}
