<?php

/**
 * This file is part of the Yep package.
 * Copyright (c) 2018 Martin Zeman (Zemistr) (http://www.zemistr.eu)
 */

declare(strict_types=1);

namespace Yep\Reflection;


final class ReflectionClass extends \ReflectionClass
{

	/** @var object */
	protected $object;


	/**
	 * @param object|mixed $class
	 * @param object|mixed|null $object
	 * @throws \ReflectionException
	 */
	public function __construct($class, $object = null)
	{
		if (is_object($class) && !is_object($object)) {
			$object = $class;
		}
		if (!is_object($object)) {
			throw new \InvalidArgumentException('Expected "object", got "' . gettype($object) . '".');
		}

		parent::__construct($class);
		$this->object = $object;
	}


	/**
	 * @param object|mixed $class
	 * @param object|mixed|null $object
	 * @return self
	 * @throws \ReflectionException
	 */
	public static function from($class, $object = null): self
	{
		return new self($class, $object);
	}


	public function getObject()
	{
		return $this->object;
	}


	/**
	 * @param string $method
	 * @param mixed[] $arguments
	 * @return mixed
	 */
	public function invokeMethod(string $method, array $arguments = [])
	{
		try {
			$reflection = $this->getMethod($method);
		} catch (\ReflectionException $e) {
			throw new \InvalidArgumentException('Reflection: Method "' . $method . '" does not exist: ' . $e->getMessage(), $e->getCode(), $e);
		}
		$reflection->setAccessible(true);

		return $reflection->invokeArgs($this->object, $arguments);
	}


	/**
	 * @param mixed $value
	 * @return self
	 */
	public function setPropertyValue(string $property, $value): self
	{
		try {
			$reflection = $this->getProperty($property);
		} catch (\ReflectionException $e) {
			throw new \InvalidArgumentException('Reflection: Property "' . $property . '" does not exist: ' . $e->getMessage(), $e->getCode(), $e);
		}
		$reflection->setAccessible(true);
		$reflection->setValue($this->object, $value);

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getPropertyValue(string $property)
	{
		try {
			$reflection = $this->getProperty($property);
		} catch (\ReflectionException $e) {
			throw new \InvalidArgumentException('Reflection: Property "' . $property . '" does not exist: ' . $e->getMessage(), $e->getCode(), $e);
		}
		$reflection->setAccessible(true);

		return $reflection->getValue($this->object);
	}


	/**
	 * @return self
	 * @throws \ReflectionException
	 */
	public function getParent(): self
	{
		return self::from($this->getParentClass()->getName(), $this->object);
	}
}
