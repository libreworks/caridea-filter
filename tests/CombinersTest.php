<?php
namespace Caridea\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-16 at 14:26:12.
 */
class CombinersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Caridea\Filter\Combiners::appender
     */
    public function testAppender()
    {
        $f = Combiners::appender('choices', 'choices-');
        $input = ['choices-0' => '1', 'choices-1' => '2', 'choices-2' => '3'];
        $output = ['choices' => ['1', '2', '3']];
        $this->assertEquals($output, $f($input));
    }

    /**
     * @covers Caridea\Filter\Combiners::appender
     */
    public function testAppenderBlank()
    {
        $f = Combiners::appender('choices', 'choices-');
        $input = ['cheers' => 'where everybody knows your name'];
        $this->assertEquals($input, $f($input));
    }

    /**
     * @covers Caridea\Filter\Combiners::prefixed
     */
    public function testPrefixed()
    {
        $f = Combiners::prefixed('address', 'address-');
        $input = ['address-street' => '123 Main St', 'address-city' => 'Chicago'];
        $output = ['address' => ['street' => '123 Main St', 'city' => 'Chicago']];
        $this->assertEquals($output, $f($input));
    }

    /**
     * @covers Caridea\Filter\Combiners::prefixed
     */
    public function testPrefixedBlank()
    {
        $f = Combiners::prefixed('address', 'address-');
        $input = ['poc-street' => '123 Main St'];
        $this->assertEquals($input, $f($input));
    }

    /**
     * @covers Caridea\Filter\Combiners::datetime
     */
    public function testDatetimeNoZone()
    {
        $f = Combiners::datetime('start', 'start-date', 'start-time');
        $input = ['start-date' => '2016-09-16', 'start-time' => 'T14:34:12'];
        $tz = new \DateTimeZone(date_default_timezone_get());
        $output = ['start' => new \DateTime('2016-09-16T14:34:12', $tz)];
        $this->assertEquals($output, $f($input));
        $this->assertEquals($tz, $output['start']->getTimezone());
    }

    /**
     * @covers Caridea\Filter\Combiners::datetime
     */
    public function testDatetimeZone()
    {
        $f = Combiners::datetime('start', 'start-date', 'start-time', 'start-zone');
        $input = ['start-date' => '2016-09-16', 'start-time' => 'T14:34:12', 'start-zone' => 'America/Los_Angeles'];
        $laz = new \DateTimeZone('America/Los_Angeles');
        $output = ['start' => new \DateTime('2016-09-16T14:34:12', $laz)];
        $this->assertEquals($output, $f($input));
        $this->assertEquals($laz, $output['start']->getTimezone());
    }

    /**
     * @covers Caridea\Filter\Combiners::datetime
     */
    public function testDatetimeBlank()
    {
        $f = Combiners::datetime('start', 'start-date', 'start-time');
        $input = ['end-date' => '2016-09-16', 'end-time' => 'T14:34:12'];
        $this->assertEquals($input, $f($input));
    }
}
