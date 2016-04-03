<?php namespace Serve;

abstract class Controller implements \Interfaces\Controller
{
	public $tools = array();
	public $route = null;

	public function add_tool($key, $tool) {
		$this->tools[$key] = $tool;
	}

	public function set_route($route) {
		$this->route = $route;
	}

	abstract protected function run();
}
