<?php

namespace GodsDev\MyCMS\Test;

use GodsDev\MyCMS\MyCMS;
use GodsDev\MyCMS\MyController;

require_once __DIR__ . '/../conf/config.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-12-30 at 10:24:46.
 */
class MyControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MyCMS
     */
    protected $myCms;

    /**
     * @var MyController
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * 
     * @global type $backyardConf
     */
    protected function setUp()
    {
        global $backyardConf;
        $backyard = new \GodsDev\Backyard\Backyard($backyardConf);
        $mycmsOptions = array(
            'TRANSLATIONS' => array(
                'en' => 'English',
                'cn' => '中文'
            ),
            'logger' => $backyard->BackyardError,
        );
        $this->myCms = new MyCMS($mycmsOptions);
        //$this->object = new MyController;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers GodsDev\MyCMS\MyController::controller
     */
    public function testControllerNoContext()
    {
        $this->object = new MyController($this->myCms);
        $this->assertEquals(['template' => 'home', 'context' => ['pageTitle' => '']], $this->object->controller());
    }

    /**
     * @covers GodsDev\MyCMS\MyController::controller
     */
    public function testControllerContext()
    {
        $this->myCms->context = array("1" => "2", "3" => "4", "c");
        $this->object = new MyController($this->myCms);
        $this->assertEquals(array("template" => "home", "context" => $this->myCms->context), $this->object->controller());
    }

    /**
     * @covers GodsDev\MyCMS\MyController::getVars
     */
    public function testGetVars()
    {
        $this->myCms->context = array("1" => "2", "3" => "4", "c");
        $options = array(
            "get" => array("v1" => "getSth"),
            "session" => array("v1" => "getSth"),
            //"section_styles" => array("red", "blue",),
        );
        $this->object = new MyController($this->myCms, $options);
        $this->assertEquals($options, $this->object->getVars());
    }

}
