<?php

namespace Shorten\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder, Symfony\Component\Console\Input\InputOption;
use Shorten\Service\ShortenerService;
class ListLinksCommand extends Command {
	protected $shortener;
	function __construct(ShortenerService $shortener, $name = null) {
		parent::__construct($name);
		$this->shortener = $shortener;
	}
	protected function configure() {
		$this->setName('app:link:list')
				->setDescription('List application links')
				->setDefinition(
						array(
								new InputOption('limit', "l",
										InputOption::VALUE_OPTIONAL,
										'The number of links to display', "10"),));
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		/* @var $shortener \Shorten\Service\ShortenerService */
		$app = $this->getHelper("app")->getApplication();
		$shortener = $this->shortener;
		$links = $shortener
				->findAllLinks(null, null, $input->getOption("limit"), null);
		$output
				->writeln(
						$app["twig"]
								->render("command/links_index.cli.twig",
										array("links" => $links)));
	}
}
