<?php
declare(strict_types=1);

namespace Yep\Reflection;

class ReflectionClass extends \ReflectionClass
{
    /** @var object */
    protected $object;

    public function __construct($class, $object = null)
    {
        if (is_object($class) && !is_object($object)) {
            $object = $class;
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(
              sprintf('Expected "object", got "%s".', gettype($object))
            );
        }

        parent::__construct($class);
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }

    public static function from($class, $object = null): self
    {
        return new static($class, $object);
    }

    public function invokeMethod(string $method, array $arguments = [])
    {
        $reflection = $this->getMethod($method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($this->object, $arguments);
    }

    public function setPropertyValue(string $property, $value): self
    {
        $reflection = $this->getProperty($property);
        $reflection->setAccessible(true);
        $reflection->setValue($this->object, $value);

        return $this;
    }

    public function getPropertyValue(string $property)
    {
        $reflection = $this->getProperty($property);
        $reflection->setAccessible(true);

        return $reflection->getValue($this->object);
    }

    public function getParent()
    {
        return self::from($this->getParentClass()->getName(), $this->object);
    }
}
