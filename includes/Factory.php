<?php

namespace PerryRylance\WordPress;

trait Factory
{
	public static function createInstance()
	{
		$class = get_called_class();
		$args = func_get_args();
		$filter = "factory_create_instance_of_$class";

		// TODO: If the created object is a descendant of CRUD 
		if(empty($args))
			$filter_args = array($filter, null);
		else
			$filter_args = array_merge(array($filter), $args);
		
		$override = call_user_func_array('apply_filters', $filter_args);

		// NB: This stops override being the same as the first argument
		if(count($args) && $args[0] === $override)
			$override = null;
		
		if(self::isUsingFactoryTrait($override))
			return $override;
		
		$reflect = new \ReflectionClass($class);
		$instance = $reflect->newInstanceArgs($args);
		
		return $instance;
	}

	private static function isUsingFactoryTrait(string $class): bool
	{
		return in_array(
			self::class, 
			array_keys((new \ReflectionClass($class))->getTraits())
		);
	}
}