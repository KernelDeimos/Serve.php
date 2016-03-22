<?php

// Include ServePHP Internal Classes
{
	$CLASSES = realpath(dirname(__FILE__)).
		DIRECTORY_SEPARATOR.'internal'.DIRECTORY_SEPARATOR;

	require_once($CLASSES.'IncludePathHandler.class.php');
}


class ServePHP {

	public static PATH_ROOT;
	public static PATH_SERVE;
	public static PATH_WEB;

	private function _boot() {
		// Determine FRAMEWORK_PATH and ROOT_PATH
		self::FRAMEWORK_PATH = realpath(dirname(__FILE__));
		self::ROOT_PATH      = getcwd();

		// Determine root URL of project
		$pattern = '/^'.preg_quote($_SERVER['DOCUMENT_ROOT'],'/').'/';
		$webpath = "http://".$_SERVER['HTTP_HOST'].preg_replace($pattern,'',getcwd());
		self::WEB_PATH = $webpath;

		// Register the autoload handler
		{
			$serve_autoload = function ($className) {
				$classPathAndName = $className;
				// Get only name of class if there's a namespace
				$className = explode("\\", $className);
				$className = $className[count($className)-1];
				// Generate possible paths for file
				$possibleLocations = array();
				$possibleLocations[] = $className . '.class.php';
				$possibleLocations[] = $className.".class/main.php";
				$possibleLocations[] = $className."/".$className.".php";
				$possibleLocations[] = $className.".php"; // (as a last resort)
				// Attempt to include each file
				foreach ($possibleLocations as $location) {
					@include($location);
					if (class_exists($className)) return;
				}
				// Throw error if class still isn't loaded
				if (!class_exists($className)) {
					throw new Exception (
						"Autoload failed; no file or folder with the given classname (".$className.") was readable"
					);
				}
			}
			spl_autoload_register($serve_autoload);
		}

		// Add Serve's default autoload directory
		IncludePathHandler::add_include_path(
			self::FRAMEWORK_PATH.DIRECTORY_SEPARATOR."framework"
		);
	}

	function __construct() {
		$this->_boot();
	}
}
