# WordPressClassFactory
A small class implemeting the factory method using WordPress' filter system, designed for making WordPress classes extensible.

## Installation

`composer require perry-rylance/wordpress-class-factory`

## Usage

Let's imagine we have a use case where plugin B would like to extend one of plugin A's classes, allowing plugin A to instantiate plugin B's class without any knowledge of it.

### Plugin A
```
namespace ExamplePlugin;

use PerryRylance\WordPress\Factory;

class Vehicle extends Factory
{
	public function name(): string
	{
		return "A generic vehicle";
	}
}

add_action('init', function() {

	$vehicle = Vehicle::createInstance();

	echo $vehicle->name();

	exit;

});
```

### Plugin B
```
class Camper extends Vehicle
{
	public function name(): string
	{
		return "A wonderful camper";
	}
}

Vehicle::override(fn() => return new Camper());
```

The above will output `"A wonderful camper"`.