<?php

namespace Mparaiso\Shortener\Command;

use Shorten\Service\ShortenerService;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder;

class ShortenCommand extends Command
{
    /**
     * @var Shorten\Service\ShortenerService $shortener
     */
    protected $shortener;

    function __construct($name = NULL)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addArgument("url", InputArgument::REQUIRED, "the url to shroten")
            ->addOption("custom", "c", InputArgument::OPTIONAL, "a custom shortened link")
            ->setName('app:shorten')
            ->setDescription('Shorten a url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app  = $this->getHelper("app")->getApplication();
        $ns   = $app["url_shortener.ns"];
        $link = $app[$ns . 'shortener_service']
            ->shorten($input->getArgument("url"), $input->getOption("custom"));
        $output->writeln(Debug::dump($link));
    }
}
