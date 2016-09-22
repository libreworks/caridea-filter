<?php
namespace Caridea\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-16 at 13:27:19.
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Caridea\Filter\Filter::__invoke
     * @covers Caridea\Filter\Filter::__construct
     */
    public function test__invoke()
    {
        $registry = new Registry();
        $c1 = new Chain($registry, false);
        $c1->then('trim')->then('titlecase');
        $c2 = new Chain($registry, true);
        $c2->then('string')->then('default', 0);
        $r = $this->createMock(Reducer::class);
        $r->expects($this->once())->method('__invoke')->willReturnArgument(0);
        $filter = new Filter([
            'name' => $c1,
            'age' => $c2,
        ], [$r]);
        $this->assertAttributeCount(2, 'chains', $filter);
        $this->assertAttributeCount(1, 'reducers', $filter);
        $input = ['name' => 'jonathan hawk  '];
        $output = $filter($input);
        $this->assertEquals(['name' => 'Jonathan Hawk', 'age' => 0], $output);
    }

    /**
     * @covers Caridea\Filter\Filter::__invoke
     * @covers Caridea\Filter\Filter::__construct
     */
    public function test__invokeOtherwise()
    {
        $registry = new Registry();
        $c1 = new Chain($registry, false);
        $c1->then('trim')->then('titlecase');
        $c2 = new Chain($registry, true);
        $c2->then('string')->then('default', 0);
        $r = $this->createMock(Reducer::class);
        $r->expects($this->once())->method('__invoke')->willReturnArgument(0);
        $chains = ['name' => $c1, 'age' => $c2];
        $filter = new Filter($chains, [$r], (new Chain($registry, false))->then('trim'));
        $this->assertAttributeCount(2, 'chains', $filter);
        $this->assertAttributeCount(1, 'reducers', $filter);
        $input = ['name' => 'jonathan hawk  ', 'foo' => '   bar   '];
        $output = $filter($input);
        $this->assertEquals(['name' => 'Jonathan Hawk', 'age' => 0, 'foo' => 'bar'], $output);
    }

    /**
     * @covers Caridea\Filter\Filter::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must be an instance of Chain
     */
    public function test__construct()
    {
        new Filter(['foo' => 'bar']);
    }

    /**
     * @covers Caridea\Filter\Filter::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must be an instance of Reducer
     */
    public function test__construct2()
    {
        new Filter([], ['foobar']);
    }
}
