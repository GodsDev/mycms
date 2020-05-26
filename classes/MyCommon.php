<?php

namespace GodsDev\MyCMS;

use Tracy\Debugger;

/**
 * Generic ancestor for classes that uses MyCMS
 * 
 */
class MyCommon
{

    const TEMPLATE_NOT_FOUND = 'error404'; // MUST be the same both for Controller extends MyController extends MyCommon and MyFriendlyUrl extends MyCommon
    const TEMPLATE_DEFAULT = 'home'; // MUST be the same both for Controller extends MyController extends MyCommon and MyFriendlyUrl extends MyCommon

    use \Nette\SmartObject;

    /** @var \GodsDev\MyCMS\MyCMS */
    protected $MyCMS;

    /**
     * Bleeds information
     * false - nothing, true - Debugger::barDump()
     * 
     * @var bool
     */
    protected $verbose = false;

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

    /**
     * Dumps information about a variable in Tracy Debug Bar or is silent
     * 
     * @param  mixed  $var
     * @param  string $title
     * @param  array  $options of Debugger::barDump (Dumper::DEPTH, Dumper::TRUNCATE, Dumper::LOCATION, Dumper::LAZY)
     * @return mixed  variable itself
     */
    protected function verboseBarDump($var, $title = null, array $options = [])
    {
        if ($this->verbose === true) { //TODO change to loose condition
            return Debugger::barDump($var, $title, $options);
        }
        return $var;
    }

}
