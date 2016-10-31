<?php
declare(strict_types = 1);

use Yep\Reflection\ReflectionClass;

class ReflectionClassTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $class = new TestClass();

        $reflection_class = new ReflectionClass($class);
        $reflection_property = new ReflectionProperty($reflection_class, 'object');
        $reflection_property->setAccessible(true);

        $this->assertSame($class, $reflection_property->getValue($reflection_class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithException()
    {
        new ReflectionClass('TestClass');
    }

    public function testFrom()
    {
        $class = new TestClass();

        $reflection_class = ReflectionClass::from($class);
        $reflection_property = new ReflectionProperty($reflection_class, 'object');
        $reflection_property->setAccessible(true);

        $this->assertSame($class, $reflection_property->getValue($reflection_class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFromWithException()
    {
        ReflectionClass::from('TestClass');
    }

    public function testInvokeMethod()
    {
        $class = new TestClass();

        $this->assertSame('protected:foo', ReflectionClass::from($class)->invokeMethod('testProtectedMethod', ['foo']));
        $this->assertSame('protected:foo#bar', ReflectionClass::from($class)->invokeMethod('testProtectedMethod', ['foo', 'bar']));

        $this->assertSame('private:foo', ReflectionClass::from($class)->invokeMethod('testPrivateMethod', ['foo']));
        $this->assertSame('private:foo#bar', ReflectionClass::from($class)->invokeMethod('testPrivateMethod', ['foo', 'bar']));
    }

    public function testSetPropertyValue()
    {
        $class = new TestClass();

        $this->assertNull($class->getTestProperty());
        ReflectionClass::from($class)->setPropertyValue('test_property', 'foo');
        $this->assertSame('foo', $class->getTestProperty());
    }

    public function testGetPropertyValue()
    {
        $class = new TestClass();

        $this->assertNull($class->getTestProperty());
        $this->assertNull(ReflectionClass::from($class)->getPropertyValue('test_property'));

        $class->setTestProperty('foo');

        $this->assertSame('foo', ReflectionClass::from($class)->getPropertyValue('test_property'));
    }
}

class TestClass
{
    protected $test_property;

    protected function testProtectedMethod()
    {
        return 'protected:'.implode('#', func_get_args());
    }

    private function testPrivateMethod()
    {
        return 'private:'.implode('#', func_get_args());
    }

    public function getTestProperty()
    {
        return $this->test_property;
    }

    public function setTestProperty($test_property)
    {
        $this->test_property = $test_property;
    }
}
