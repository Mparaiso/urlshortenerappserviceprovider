<?php
namespace Shorten\Command\Helper;

use Symfony\Component\Console\Helper\Helper;

class ApplicationHelper extends Helper {
	protected $app;
	function __construct(\Silex\Application $app){
		$this->app=$app;
	}

	function getApplication(){
		return $this->app;
	}

	function getName() {
		return "application";

	}

}