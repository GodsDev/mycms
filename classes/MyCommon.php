<?php

namespace GodsDev\MyCMS;

/**
 * Generic ancestor for classes that uses MyCMS
 * 
 */
class MyCommon
{

    use \Nette\SmartObject;

    /** @var \GodsDev\MyCMS\MyCMS */
    protected $MyCMS;

    /**
     * 
     * @param \GodsDev\MyCMS\MyCMS $MyCMS
     * @param array $options overrides default values of declared properties
     */
    public function __construct(MyCMS $MyCMS, array $options = [])
    {
        foreach ($options as $optionVariable => $optionContent) {
            if (property_exists($this, $optionVariable)) {
                $this->{$optionVariable} = $optionContent;
            }
        }
        $this->MyCMS = $MyCMS;
    }

}
