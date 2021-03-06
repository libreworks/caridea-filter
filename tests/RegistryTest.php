<?php
namespace Caridea\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-16 at 12:57:17.
 */
class RegistryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture
     */
    protected function setUp()
    {
        $this->object = new Registry();
    }

    /**
     * @covers Caridea\Filter\Registry::__construct
     * @covers Caridea\Filter\Registry::register
     */
    public function testRegister()
    {
        $this->object->register(['test' => function ($b, $c) {
            $this->assertEquals('foo', $b);
            $this->assertEquals('bar', $c);
            return function ($a) {
                return $a;
            };
        }]);
        $f = $this->object->factory('test', ['foo', 'bar']);
        $this->assertSame($this, $f($this));
    }

    /**
     * @covers Caridea\Filter\Registry::register
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Values passed to register must be callable
     */
    public function testRegisterBad()
    {
        $this->object->register(['test' => 123]);
    }

    /**
     * @covers Caridea\Filter\Registry::__construct
     * @covers Caridea\Filter\Registry::factory
     */
    public function testFactory()
    {
        $f = $this->object->factory('trim', []);
        $this->assertTrue(is_callable($f));
        $this->assertEquals('foobar', $f('    foobar  '));
    }

    /**
     * @covers Caridea\Filter\Registry::__construct
     * @covers Caridea\Filter\Registry::factory
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No filter registered with name: foobar
     */
    public function testFactoryMissing()
    {
        $this->object->factory('foobar', []);
    }

    /**
     * @covers Caridea\Filter\Registry::__construct
     * @covers Caridea\Filter\Registry::factory
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Definitions must return callable
     */
    public function testFactoryUncallable()
    {
        $this->object->register(['foobar' => function () {
            return 'hi';
        }]);
        $this->object->factory('foobar', []);
    }

    /**
     * @covers Caridea\Filter\Registry::builder
     */
    public function testBuilder()
    {
        $registry = new Registry();
        $builder = $registry->builder();
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertAttributeSame($registry, 'registry', $builder);
    }
}
