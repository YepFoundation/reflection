<?php

/**
 * This file is part of the Yep package.
 * Copyright (c) 2018 Martin Zeman (Zemistr) (http://www.zemistr.eu)
 */

declare(strict_types=1);


use Yep\Reflection\ReflectionClass;

class ReflectionClassTest extends PHPUnit_Framework_TestCase
{
	public function testConstructor(): void
	{
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
	public function testConstructorWithException(): void
	{
		new ReflectionClass('TestClass');
	}


	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructorWithExceptionOnObject(): void
	{
		new ReflectionClass('TestClass', 'TestClass');
	}


	public function testFrom(): void
	{
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
	public function testFromWithException(): void
	{
		ReflectionClass::from('TestClass');
	}


	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromWithExceptionOnObject(): void
	{
		ReflectionClass::from('TestClass', 'TestClass');
	}


	public function testInvokeMethod(): void
	{
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


	public function testSetPropertyValue(): void
	{
		$class = new TestClass();

		$this->assertSame(1, $class->getTestProperty());
		ReflectionClass::from($class)->setPropertyValue('testProperty', 'foo');
		$this->assertSame('foo', $class->getTestProperty());
	}


	public function testGetPropertyValue(): void
	{
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


	public function testGetParent(): void
	{
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


	public function testGetObject(): void
	{
		$class = new TestClass();
		$reflection = ReflectionClass::from($class);

		$this->assertSame($class, $reflection->getObject());
	}


	private function getPropertyValue(\ReflectionClass $ref, string $name)
	{
		$property = new ReflectionProperty($ref, $name);
		$property->setAccessible(true);

		return $property->getValue($ref);
	}
}

class TestClass
{

	/** @var int|string */
	private $testProperty = 1;


	/**
	 * @return int|string
	 */
	public function getTestProperty()
	{
		return $this->testProperty;
	}


	/**
	 * @param int|string $testProperty
	 */
	public function setTestProperty($testProperty): void
	{
		$this->testProperty = $testProperty;
	}


	protected function testProtectedMethod(): string
	{
		return 'protected:' . implode('#', func_get_args());
	}


	private function testPrivateMethod(): string
	{
		return 'private:' . implode('#', func_get_args());
	}
}

class TestClass2 extends TestClass
{
	private $testProperty = 2;
}

class TestClass3 extends TestClass2
{
	private $testProperty = 3;
}
