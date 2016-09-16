<?php
namespace Caridea\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-16 at 12:44:58.
 */
class ChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Caridea\Filter\Chain::__invoke
     */
    public function test__invoke()
    {
        $registry = new Registry();
        $chain = new Chain($registry, true);
        $this->assertSame($this, $chain($this));
    }
    
    /**
     * @covers Caridea\Filter\Chain::__invoke
     */
    public function test__invoke2()
    {
        $registry = new Registry();
        $chain = new Chain($registry, true);
        $chain = $chain->then('trim');
        $this->assertEquals('foobar', $chain('    foobar  '));
    }

    /**
     * @covers Caridea\Filter\Chain::isEmpty
     * @covers Caridea\Filter\Chain::count
     * @covers Caridea\Filter\Chain::getIterator
     */
    public function testArrayStuff()
    {
        $registry = new Registry();
        $chain = new Chain($registry, true);
        $this->assertTrue($chain->isEmpty());
        $this->assertEquals(0, count($chain));
        $chain = $chain->then('trim')->then('lowercase');
        $this->assertFalse($chain->isEmpty());
        $this->assertEquals(2, count($chain));
        $this->assertInstanceOf(\ArrayIterator::class, $chain->getIterator());
        foreach ($chain as $v) {
            $this->assertTrue(is_callable($v));
        }
    }

    /**
     * @covers Caridea\Filter\Chain::__construct
     * @covers Caridea\Filter\Chain::isRequired
     */
    public function testIsRequired()
    {
        $registry = new Registry();
        $chain = new Chain($registry, true);
        $this->assertTrue($chain->isRequired());
        $chain = new Chain($registry, false);
        $this->assertFalse($chain->isRequired());
    }

    /**
     * @covers Caridea\Filter\Chain::then
     */
    public function testThen()
    {
        $registry = new Registry();
        $chain = new Chain($registry, true);
        $this->assertInstanceOf(Chain::class, $chain->then('trim'));
    }
}
