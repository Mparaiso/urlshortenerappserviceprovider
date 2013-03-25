<?php

namespace Shorten\Command;

use Symfony\Component\Process\Process;

use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder;

class FlushCacheCommand extends Command
{
	protected $cachePath;
	function __construct($cachePath,$name=null) {
		parent::__construct($name);
		$this->cachePath= $cachePath;
	}
    protected function configure()
    {
        $this
        ->setName('app:cache:delete')
        ->setDescription("erase cache folder $this->cachePath ");
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $proc = new Process("rm -rf $this->cachePath/* ");
        $proc->run();
        $output->writeln("The content of the cache folder \n '$this->cachePath' \nhas been deleted");
    }
}
