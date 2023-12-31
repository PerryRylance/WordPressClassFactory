<?php

namespace PerryRylance\WordPress;

trait Factory
{
	const FILTER_PREFIX = "factory_create_instance_of_";

	public static function createInstance()
	{
		$class = get_called_class();
		$args = func_get_args();
		$filter = static::FILTER_PREFIX . $class;

		// TODO: If the created object is a descendant of CRUD 
		if(empty($args))
			$filter_args = array($filter, null);
		else
			$filter_args = array_merge(array($filter), $args);
		
		$override = call_user_func_array('apply_filters', $filter_args);

		// NB: This stops override being the same as the first argument
		if(count($args) && $args[0] === $override)
			$override = null;
		
		$isUsingFactoryTrait = in_array(
			"PerryRylance\\WordPress\\Factory",
			array_keys((new \ReflectionClass($class))->getTraits())
		);

		if(!is_null($override) && $isUsingFactoryTrait)
			return $override;
		
		$reflect = new \ReflectionClass($class);
		$instance = $reflect->newInstanceArgs($args);
		
		return $instance;
	}

	public static function override(callable $create, int $priority = 10, int $accepted_args = 0)
	{
		add_filter(static::FILTER_PREFIX . get_called_class(), $create, $priority, $accepted_args);
	}
}