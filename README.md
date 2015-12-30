[![Build Status](https://travis-ci.org/YepFoundation/reflection.svg?branch=master)](https://travis-ci.org/YepFoundation/reflection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/YepFoundation/reflection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/reflection/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/YepFoundation/reflection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/reflection/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yep/reflection/v/stable)](https://packagist.org/packages/yep/reflection)
[![Total Downloads](https://poser.pugx.org/yep/reflection/downloads)](https://packagist.org/packages/yep/reflection)
[![License](https://poser.pugx.org/yep/reflection/license)](https://github.com/YepFoundation/reflection/blob/master/LICENSE.md)

# PHP class reflection enhancements

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

### "Test subject"
```php
class SomeClass {
	protected $some_property;

	protected function someMethod($some_argument) {
		return $some_argument;
	}

	public function getSomeProperty() {
		return $this->some_property;
	}

	public function setSomeProperty($some_property) {
		$this->some_property = $some_property;
	}
}
```

### You can simply call the protected or private method

```php
$some_class = new SomeClass();

echo \Yep\Reflection\ReflectionClass::from($class = $some_class)->invokeMethod($method = 'someMethod', $arguments = ['foo']); // 'foo'
```

### You can simply set value to the protected or private property

```php
$some_class = new SomeClass();

\Yep\Reflection\ReflectionClass::from($class = $some_class)->setPropertyValue($property = 'some_property', $value = 'foo');

echo $some_class->getSomeProperty(); // 'foo'
```

### You can simply get value from the protected or private property

```php
$some_class = new SomeClass();
$some_class->setSomeProperty('foo');

echo \Yep\Reflection\ReflectionClass::from($class = $some_class)->getPropertyValue($property = 'some_property'); // 'foo';
```
