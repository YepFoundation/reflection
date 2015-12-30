<?php
namespace Yep\Reflection;

class ReflectionClass extends \ReflectionClass {
	/** @var object */
	protected $object;

	public function __construct($object) {
		if (!is_object($object)) {
			throw new \InvalidArgumentException(sprintf('Expected "object", got "%s".', gettype($object)));
		}

		parent::__construct($object);
		$this->object = $object;
	}

	public static function from($class) {
		return new static($class);
	}

	public function invokeMethod($method, array $arguments = []) {
		$reflection = $this->getMethod($method);
		$reflection->setAccessible(true);

		return $reflection->invokeArgs($this->object, $arguments);
	}

	public function setPropertyValue($property, $value) {
		$reflection = $this->getProperty($property);
		$reflection->setAccessible(true);
		$reflection->setValue($this->object, $value);
	}

	public function getPropertyValue($property) {
		$reflection = $this->getProperty($property);
		$reflection->setAccessible(true);

		return $reflection->getValue($this->object);
	}
}
