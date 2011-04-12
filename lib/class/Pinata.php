<?php

class Pinata {

	private static $before = NULL;
	private static $after = NULL;
	private static $paths = array();
	private static $layout = '';
	private static $output = '';
	private static $params = array();
	private static $options = array();
	private static $instance = NULL;

	private function __construct()
	{
		register_shutdown_function(array($this, 'run'));
	}
	private function __clone() {}

	/**
	 * @static
	 * @return Pinata
	 */
	public static function getInstance()
	{
		if (self::$instance == NULL)
			self::$instance = new self();

		return self::$instance;
	}

	public static function setOption($option, $value)
	{
		self::$options[$option] = $value;
	}

	public static function getOption($option)
	{
		return isset(self::$options[$option])?self::$options[$option]:false;
	}

	public static function addPath($method, $pattern, $condition_or_callback, $callback = NULL)
	{
		if (gettype($condition_or_callback) == 'boolean')
		{
			if ($condition_or_callback == false)
				return false;
		} else {
			$callback = $condition_or_callback;
		}

		self::$paths[$method][$pattern] = $callback;
	}

	public static function setLayout($layout)
	{
		self::$layout = $layout;
	}

	public static function setBefore($before)
	{
		self::$before = $before;
	}

	public static function setAfter($after)
	{
		self::$after = $after;
	}

	public static function getContent()
	{
		return self::$output;
	}

	private function getPath()
	{
		return $_GET['q'];
	}

	public static function getParams()
	{
		return self::$params;
	}

	public function route($route)
	{
		$_GET['q'] = $route;
		$_layout = self::$layout;
		self::$layout = '';
		$output = $this->run();
		self::$layout = $_layout;
		unset($_layout);
		return $output;
	}

	public function run()
	{
		ob_start();
		foreach (self::$paths[$_SERVER['REQUEST_METHOD']] as $pattern => $callback)
		{
			if ($pattern != $this->getPath() && !preg_match("#^$pattern#u", $this->getPath(), $params))
				continue;

			self::$params = $params;
			//Getting callback's type to define its closure, method, or else...
			switch (gettype($callback))
			{
				case 'array':
				case 'string':
					call_user_func($callback, $params);
					break;
				case 'object':
					switch (get_class($callback))
					{
						case 'Closure': //it's closure.
							/** @var $callback Closure */
							$response = $callback();

							//passing
							if ($response === false)
								continue;
								
							break 3;
						default:
							$className = get_class($callback);
							$class = new ReflectionClass($className);
							if (!$class->isAbstract())
							{
								$response =  $className($params);
							} elseif ($class->isAbstract() && $class->hasMethod('getInstance')) {
								$response = $className::getInstance();
							} else {
								$response = false;
							}

							if ($response === false)
								continue;
								
							break 3;
					}
					break;
			}
		}
		self::$output = ob_get_clean();
		if (self::$layout != '')
		{
			if (self::$before instanceof Closure)
			{
				$callback = self::$before;
				$callback();
			}
			include self::$layout;
			if (self::$after instanceof Closure)
			{
				$callback = self::$after;
				$callback();
			}
		} else {
			return self::$output;
		}
	}

}