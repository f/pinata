<?php
include 'class/Pinata.php';
Pinata::getInstance();

#routes

function get($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('GET', $pattern, $condition_or_callback, $callback);
}

function put($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('PUT', $pattern, $condition_or_callback, $callback);
}

function post($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('POST', $pattern, $condition_or_callback, $callback);
}

function patch($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('PATCH', $pattern, $condition_or_callback, $callback);
}

function delete($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('DELETE', $pattern, $condition_or_callback, $callback);
}

function options($pattern, $condition_or_callback, $callback = NULL)
{
	Pinata::addPath('OPTIONS', $pattern, $condition_or_callback, $callback);
}

#config

function option($key, $value = false)
{
	if ($value !== false)
		Pinata::setOption($key, $value);
	else
		return Pinata::getOption($key);
}

#params

function param($key)
{
	$params = Pinata::getParams();
	return $params[$key];
}

function route($route)
{
	return Pinata::getInstance()->route($route);
}

function status($code)
{
	//TODO Implement
}

function headers(array $headers)
{
	//TODO Implement
}

function redirect($location)
{
	
}

function redirect_back()
{
	
}

function attachment($filename = NULL)
{
	
}

function assign($variable, $value)
{

}

function display($view)
{
	
}

/**
 *
 * Runs or raises an error.
 *
 * @param  $code
 * @param null $callback
 * @return void
 */
function error($code_or_codes, $callback = NULL)
{

}

function before($callback)
{
	Pinata::setBefore($callback);
}

function after($callback)
{
	Pinata::setAfter($callback);
}

#views

function layout($layout)
{
	Pinata::setLayout(realpath($layout));
}

function content()
{
	return Pinata::getContent();
}