<?php

namespace Mparaiso\Shortener\Command;

use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder;

class ServerCommand extends Command
{
    protected function configure()
    {
        $this
        ->addArgument("port",InputArgument::OPTIONAL,"post","8080")
        ->addOption("webroot","w",InputArgument::OPTIONAL,"the web root","web")
        ->addOption("router","r",InputArgument::OPTIONAL,"the router script","web/index_dev.php")
        ->setName('server:run')
        ->setDescription('Run server on localhost:port , default port is 8080')
        ->setHelp("
The <info>%command.name%</info> run default php server on localhost:port.
default port is 8080
            ");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument("port");
        $root = $input->getOption("webroot");
        $router = $input->getOption("router");
        /*$builder = new ProcessBuilder(array(PHP_BINARY,"-Slocalhost:".$port,"-t",$root));
        $builder->getProcess()->run(function($type,$buffer)use($output){
                $output->write($buffer);
        }
        );*/
        $output->writeln("Running server on localhost:$port ...");
        $fp=popen("php -Slocalhost:$port -t $root $router","r");
        while (!feof($fp)) { 
            $buffer = fgets($fp, 4096); 
            $output->write($buffer);
        } 
        pclose($fp); 
    }
}
