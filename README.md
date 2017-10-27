[![Build Status](https://travis-ci.org/YepFoundation/reflection.svg?branch=master)](https://travis-ci.org/YepFoundation/reflection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/YepFoundation/reflection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/reflection/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/YepFoundation/reflection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/reflection/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yep/reflection/v/stable)](https://packagist.org/packages/yep/reflection)
[![Total Downloads](https://poser.pugx.org/yep/reflection/downloads)](https://packagist.org/packages/yep/reflection)
[![License](https://poser.pugx.org/yep/reflection/license)](https://github.com/YepFoundation/reflection/blob/master/LICENSE.md)

# PHP class reflection enhancements ([docs](http://yepfoundation.github.io/reflection))

## Packagist
Reflection is available on [Packagist.org](https://packagist.org/packages/yep/reflection),
just add the dependency to your composer.json.

```json
{
  "require" : {
    "yep/reflection": "1.*"
  }
}
```

or run Composer command:

```php
php composer.phar require yep/reflection
```

## What do Yep/Reflection?

### "Test subject" and example code
```php
<?php
class SomeClass {
	private $someProperty;

	protected function someMethod($someArgument) {
		return $someArgument;
	}

	public function getSomeProperty() {
		return $this->someProperty;
	}

	public function setSomeProperty($someProperty) {
		$this->someProperty = $someProperty;
	}
}

class SomeClass2 extends SomeClass {
}

$someClass = new SomeClass();
$reflection = \Yep\Reflection\ReflectionClass::from($class = $someClass);
```

### You can simply call the protected or private method

```php
<?php
$someClass = new SomeClass();

echo $reflection->invokeMethod($method = 'someMethod', $arguments = ['foo']); // 'foo'
```

### You can simply set value to the protected or private property

```php
<?php
$someClass = new SomeClass();

$reflection->setPropertyValue($property = 'someProperty', $value = 'foo');

echo $someClass->getSomeProperty(); // 'foo'
```

### You can simply get value from the protected or private property

```php
<?php
$someClass = new SomeClass();
$someClass->setSomeProperty('foo');

echo $reflection->getPropertyValue($property = 'someProperty'); // 'foo';
```


### Wanna access parent or parent's private property?

```php
<?php
$someClass = new SomeClass2();
$someClass->setSomeProperty('foo');

echo $reflection->getParent()->getPropertyValue($property = 'someProperty'); // 'foo';
```
