<?php

namespace Shorten\Command;

use Shorten\Service\CountryService;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder, Symfony\Component\Console\Input\InputOption;
use Shorten\Service\ShortenerService;
class CountByDaysCommand extends Command {
	protected $countryService;
	function __construct(CountryService $countryService, $name = null) {
		parent::__construct($name);
		$this->countryService = $countryService;
	}
	protected function configure() {
		$this->setName('app:visit:count-by-day')
				->setDescription('Count visits for a link by days , from -f date to tomorrow'),
				->addArgument("identifier",InputArgument::REQUIRED,"the link identifier"),
				->addOption("from","t",InputOption::VALUE_OPTIONAL,"from  date  Y-m-d ","yesterday");				
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		/* @var $shortener \Shorten\Service\ShortenerService */
		$app = $this->getHelper("app")->getApplication();
		/* @var $countryService \Shorten\Service\CountryService */
		$countryService = $app["country_service"];
		$today = new \DateTime("now");
		try {
			$date = new \Datetime($input->getOption("form"));
		} catch (Exception $e) {
			$output->writeln("date ${$input->getArgument('from')} is wrong format.");
		}
	
		$diff = $today->diff(new \Datetime())->format("d")
		$countryService->countByDateWith($input->getArgument("identifier"), $num_of_days)
	}
}
