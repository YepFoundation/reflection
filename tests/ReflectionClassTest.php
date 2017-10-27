<?php
use Yep\Reflection\ReflectionClass;

class ReflectionClassTest extends PHPUnit_Framework_TestCase {
	private function getPropertyValue(\ReflectionClass $ref, $name) {
		$property = new ReflectionProperty($ref, $name);
		$property->setAccessible(true);

		return $property->getValue($ref);
	}

	public function testConstructor() {
		$class = new TestClass();

		$reflection = new ReflectionClass($class);
		$this->assertSame(
			$class,
			$this->getPropertyValue($reflection, 'object')
		);

		$class2 = new TestClass2();
		$reflection = new ReflectionClass($class, $class2);
		$this->assertSame(
			$class2,
			$this->getPropertyValue($reflection, 'object')
		);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructorWithException() {
		new ReflectionClass('TestClass');
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructorWithExceptionOnObject() {
		new ReflectionClass('TestClass', 'TestClass');
	}

	public function testFrom() {
		$class = new TestClass();

		$reflection = ReflectionClass::from($class);
		$this->assertSame(
			$class,
			$this->getPropertyValue($reflection, 'object')
		);

		$class2 = new TestClass2();
		$reflection = ReflectionClass::from($class, $class2);
		$this->assertSame(
			$class2,
			$this->getPropertyValue($reflection, 'object')
		);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromWithException() {
		ReflectionClass::from('TestClass');
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromWithExceptionOnObject() {
		ReflectionClass::from('TestClass', 'TestClass');
	}

	public function testInvokeMethod() {
		$class = new TestClass();
		$reflection = ReflectionClass::from($class);

		$this->assertSame(
			'protected:foo',
			$reflection->invokeMethod('testProtectedMethod', ['foo'])
		);
		$this->assertSame(
			'protected:foo#bar',
			$reflection->invokeMethod('testProtectedMethod', ['foo', 'bar'])
		);
		$this->assertSame(
			'private:foo',
			$reflection->invokeMethod('testPrivateMethod', ['foo'])
		);
		$this->assertSame(
			'private:foo#bar',
			$reflection->invokeMethod('testPrivateMethod', ['foo', 'bar'])
		);
	}

	public function testSetPropertyValue() {
		$class = new TestClass();

		$this->assertSame(1, $class->getTestProperty());
		ReflectionClass::from($class)->setPropertyValue('testProperty', 'foo');
		$this->assertSame('foo', $class->getTestProperty());
	}

	public function testGetPropertyValue() {
		$class = new TestClass();
		$reflection = ReflectionClass::from($class);

		$this->assertSame(1, $class->getTestProperty());
		$this->assertSame(1, $reflection->getPropertyValue('testProperty'));

		$class->setTestProperty('foo');

		$this->assertSame(
			'foo',
			$reflection->getPropertyValue('testProperty')
		);
	}

	public function testGetParent() {
		$class = new TestClass3();
		$reflection = ReflectionClass::from($class);

		$class->setTestProperty(1);
		$this->assertSame(3, $reflection->getPropertyValue('testProperty'));

		$reflection = $reflection->getParent();
		$this->assertInstanceOf(ReflectionClass::class, $reflection);
		$this->assertSame(2, $reflection->getPropertyValue('testProperty'));

		$reflection = $reflection->getParent();
		$this->assertInstanceOf(ReflectionClass::class, $reflection);
		$this->assertSame(1, $reflection->getPropertyValue('testProperty'));
	}

	public function testGetObject() {
		$class = new TestClass();
		$reflection = ReflectionClass::from($class);

		$this->assertSame($class, $reflection->getObject());
	}
}

class TestClass {
	private $testProperty = 1;

	protected function testProtectedMethod() {
		return 'protected:' . implode('#', func_get_args());
	}

	private function testPrivateMethod() {
		return 'private:' . implode('#', func_get_args());
	}

	public function getTestProperty() {
		return $this->testProperty;
	}

	public function setTestProperty($testProperty) {
		$this->testProperty = $testProperty;
	}
}

class TestClass2 extends TestClass {
	private $testProperty = 2;
}

class TestClass3 extends TestClass2 {
	private $testProperty = 3;
}
