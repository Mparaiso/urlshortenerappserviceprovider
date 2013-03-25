<?php
namespace Shorten\Command;
use Silex\ControllerCollection;

use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Console\Output\Output;

use Symfony\Component\Console\Input\Input;

use Symfony\Component\Console\Command\Command;

class DumpRoutesCommand extends Command {
	function configure() {
		$this->setName("app:routes:dump")
				->setDescription("Dump all defined routes");
	}
	function execute(InputInterface $input, OutputInterface $output) {
		/* @var $app \Silex\Application */
		$app = $this->getHelper("app")->getApplication();
		$app->boot();
		/* @var $controllers \Silex\ControllerCollection */
		$controllers = $app["controllers"];
		//$controllers->
		//@TODO corriger la classe
		
	}
}
