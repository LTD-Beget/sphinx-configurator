<?php
/**
 * @author: Viskov Sergey
 * @date  : 3/17/16
 * @time  : 2:32 PM
 */

namespace LTDBeget\sphinx\informer;

use LTDBeget\sphinx\enums\base\eOption;
use LTDBeget\sphinx\enums\eSection;
use LTDBeget\sphinx\enums\eVersion;

/**
 * Class OptionInfo
 * structure class for storing option info
 *
 * @package LTDBeget\sphinx\informer
 */
final class OptionInfo
{
    /**
     * OptionInfo constructor.
     *
     * @param eOption  $name
     * @param eSection $section
     * @param eVersion $version
     * @param string   $description
     * @param bool     $isMultiValue
     * @param string   $docLink
     */
    public function __construct(
        eOption $name,
        eSection $section,
        eVersion $version,
        string $description,
        bool $isMultiValue,
        string $docLink
    )
    {
        $this->name         = $name;
        $this->section      = $section;
        $this->version      = $version;
        $this->description  = $description;
        $this->isMultiValue = $isMultiValue;
        $this->docLink      = $docLink;
    }

    /**
     * name of option
     *
     * @return eOption
     */
    public function getName() : eOption
    {
        return $this->name;
    }

    /**
     * in what section option store
     *
     * @return eSection
     */
    public function getSection() : eSection
    {
        return $this->section;
    }

    /**
     * Version of documentation info
     *
     * @return eVersion
     */
    public function getVersion() : eVersion
    {
        return $this->version;
    }

    /**
     * is Multi-value option (MVA)
     *
     * @return boolean
     */
    public function isIsMultiValue() : bool
    {
        return $this->isMultiValue;
    }

    /**
     * Get description for option
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get link on original documentation of option
     *
     * @return string
     */
    public function getDocLink() : string
    {
        return $this->docLink;
    }

    /**
     * @var eOption
     */
    private $name;

    /**
     * @var eSection
     */
    private $section;

    /**
     * @var eVersion
     */
    private $version;

    /**
     * @var bool
     */
    private $isMultiValue;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $docLink;
}