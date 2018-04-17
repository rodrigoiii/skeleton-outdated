<?php

namespace FrameworkCore\Utilities;

class Session
{
	public static function put($key, $value, $is_array = false)
	{
		if ($is_array)
		{
			$_SESSION[$key][] = $value;
		}
		else
		{
			$_SESSION[$key] = $value;
		}
	}

	public static function get($key, $flash = false)
	{
		if (isset($_SESSION[$key]))
		{
			$session_value = $_SESSION[$key];
			if ($flash)
			{
				unset($_SESSION[$key]);
			}
			return $session_value;
		}

		return false;
	}

	public static function _isSet($key)
	{
		return isset($_SESSION[$key]);
	}

	public static function isEmpty($key)
	{
		return empty($_SESSION[$key]);
	}

	public static function all()
	{
		return $_SESSION;
	}

	public static function allExcept(array $keys)
	{
		$sessions = static::all();

		if (!empty($keys))
		{
			foreach ($keys as $key) {
				unset($sessions[$key]);
			}
		}

		return $sessions;
	}

	public static function destroy(array $keys = [])
	{
		if (empty($keys))
		{
			session_destroy();
		}
		else
		{
			foreach ($keys as $key) {
				unset($_SESSION[$key]);
			}
		}
	}
}
