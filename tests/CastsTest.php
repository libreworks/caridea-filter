<?php
namespace Caridea\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-16 at 12:32:59.
 */
class CastsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Caridea\Filter\Casts::toBoolean
     */
    public function testToBoolean()
    {
        $f = Casts::toBoolean();
        $this->assertTrue($f('   T '));
        $this->assertTrue($f('   t '));
        $this->assertTrue($f(true));
        $this->assertTrue($f('on'));
        $this->assertTrue($f('true'));
        $this->assertTrue($f('yEs'));
        $this->assertTrue($f('Y'));
        $this->assertTrue($f('1'));
        $this->assertFalse($f(false));
        $this->assertFalse($f(123));
        $this->assertFalse($f([]));
        $this->assertFalse($f(new \stdClass()));
    }

    /**
     * @covers Caridea\Filter\Casts::toInteger
     */
    public function testToInteger()
    {
        $f = Casts::toInteger();
        $this->assertSame(123, $f('123'));
        $this->assertSame(123, $f(123.0));
        $this->assertSame(123, $f(123));
        $this->assertSame(1, $f(true));
        $this->assertSame(0, $f(false));
    }

    /**
     * @covers Caridea\Filter\Casts::toFloat
     */
    public function testToFloat()
    {
        $f = Casts::toFloat();
        $this->assertSame(123.0, $f('123'));
        $this->assertSame(123.0, $f(123));
        $this->assertSame(123.0, $f(123.0));
        $this->assertSame(1.0, $f(true));
        $this->assertSame(0.0, $f(false));
    }

    /**
     * @covers Caridea\Filter\Casts::toArray
     */
    public function testToArray()
    {
        $f = Casts::toArray();
        $this->assertEquals([], $f(null));
        $this->assertEquals([''], $f(''));
        $this->assertEquals([1,2,3], $f([1,2,3]));
    }

    /**
     * @covers Caridea\Filter\Casts::toDefault
     */
    public function testToDefault()
    {
        $f = Casts::toDefault(0);
        $this->assertSame(0, $f(''));
        $this->assertSame(0, $f(null));
        $this->assertSame(0, $f(0));
        $this->assertSame(123, $f(123));
        $this->assertSame([], $f([]));
    }
}
