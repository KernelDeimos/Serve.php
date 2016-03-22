<?php

class IncludePathHandler {
	static function add_include_path ($path) {
		foreach (func_get_args() AS $path)
		{
			if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
			{
				throw new Exception (
					"Include path '{$path}' does not exist"
				);
				continue;
			}
			
			// Get array from path variable
			$paths = explode(PATH_SEPARATOR, get_include_path());
			
			// Add path to array only if it doesn't exist
			if (array_search($path, $paths) === false)
				array_unshift($paths, $path);
			
			// Set new path variable from array
			set_include_path(implode(PATH_SEPARATOR, $paths));
		}
	}

	static function remove_include_path ($path) {
		// Support multiple arguments as multiple maths
		foreach (func_get_args() AS $path)
		{
			// Get array from path variable
			$paths = explode(PATH_SEPARATOR, get_include_path());
			
			// Remove path if it exists
			if (($k = array_search($path, $paths)) !== false)
				unset($paths[$k]);
			else
				continue;

			// Combine array back into string (or set to empty string if none)
			if (!count($paths))
			{
				set_include_path("");
			} else {
				set_include_path(implode(PATH_SEPARATOR, $paths));
			}
		}
	}
}