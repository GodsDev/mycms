<?php

namespace GodsDev\MyCMS;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-10-06 at 12:19:24.
 */
class MyCMSTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var MyCMS
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        require_once __DIR__ . '/../../conf/env_config.php';
        $this->object = new MyCMS;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::getSessionLanguage
     */
    public function testGetSessionLanguage() {
        
        $this->assertEquals('en', $this->object->getSessionLanguage(array('language' => 'en'), array('language' => 'en'), false), 'Fail for both languages are same en');

    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::fetchAndReindex
     * @todo   Implement testFetchAndReindex().
     */
    public function testFetchAndReindex() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers GodsDev\MyCMS\MyCMS::translate
     * @todo   Implement testTranslate().
     */
    public function testTranslate() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
