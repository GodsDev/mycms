<?php

namespace GodsDev\MyCMS\Test;

use GodsDev\MyCMS\MyCMS;

require_once __DIR__ . '/../conf/config.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-10-06 at 12:19:24.
 */
class MyCMSTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MyCMS
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
        error_reporting(E_ALL); // incl E_NOTICE
        $backyard = new \GodsDev\Backyard\Backyard($backyardConf);
        $mycmsOptions = [
            'TRANSLATIONS' => [
                'en' => 'English',
                'cn' => '中文'
            ],
            'logger' => $backyard->BackyardError,
        ];
        $this->object = new MyCMS($mycmsOptions);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // no action
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::getSessionLanguage
     */
    public function testGetSessionLanguageBasic()
    {
        $this->assertEquals(
            'en',
            $this->object->getSessionLanguage(['language' => 'en'], ['language' => 'en'], false),
            'Fail for both languages are same en'
        );
        $this->assertEquals(
            DEFAULT_LANGUAGE,
            $this->object->getSessionLanguage(['language' => 'xx'], ['language' => 'xx'], false),
            'Fail unknown language is return'
        );
        $this->assertEquals(
            'en',
            $this->object->getSessionLanguage(['language' => 'en'], ['language' => 'cn'], false),
            'get language should prevail'
        );
        $this->assertEquals(
            'cn',
            $this->object->getSessionLanguage(['language' => 'cn'], ['language' => 'en'], false),
            'get language should prevail'
        );
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::getSessionLanguage
     */
    public function testGetSessionLanguageAdvanced()
    {
        $this->assertEquals(
            'cn',
            $this->object->getSessionLanguage([], ['language' => 'cn'], false),
            'Solo session prevails'
        );
        $this->assertEquals(
            'cn',
            $this->object->getSessionLanguage(['language' => 'cn'], [], false),
            'Solo get is used'
        );
        $this->assertEquals(
            DEFAULT_LANGUAGE,
            $this->object->getSessionLanguage([], ['language' => 'xx'], false),
            'Solo wrong session is ignored'
        );
        $this->assertEquals(
            DEFAULT_LANGUAGE,
            $this->object->getSessionLanguage(['language' => 'xx'], [], false),
            'Solo wrong get is ignored'
        );
        $this->assertEquals(
            'cn',
            $this->object->getSessionLanguage(['language' => 'xx'], ['language' => 'cn'], false),
            'get language should prevail only if correct'
        );
        $this->assertEquals(
            'en',
            $this->object->getSessionLanguage(['language' => 'xx'], ['language' => 'en'], false),
            'get language should prevail only if correct'
        );
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::fetchAndReindex
     * @todo   Implement testFetchAndReindex().
     */
    public function testFetchAndReindex()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::translate
     * @todo   Implement testTranslate().
     */
    public function testTranslate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
