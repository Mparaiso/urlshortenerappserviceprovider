<?php

use Shorten\Command\CountByDaysCommand;

use Shorten\Command\DumpRoutesCommand;

use Shorten\Command\Helper\ApplicationHelper;
use Shorten\Command\ListLinksCommand;
use Symfony\Component\Console\Helper\Helper;
use Shorten\Command\FlushCacheCommand, 
	Shorten\Service\ShortenerService, 
	Symfony\Component\Console\Application, 
	Symfony\Component\Console\Helper\HelperSet, 
	Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper, 
	Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper, 
	Shorten\Command\ServerCommand, 
	Shorten\Command\ShortenCommand, 
	Symfony\Component\Console\Helper\HelperInterface;

/**
 * FR :
 * ====
 * script de la console
 *
 */
$app = require("app.php");
$app["debug"] = true;
$em = $app["em"];
$cli = new Application("Shorten console application", "0.0.1");
// Configure Doctrine ORM tool for Console cli
// Configure cli with the application
$cli->setHelperSet(new HelperSet(
	array("em" => new EntityManagerHelper($em),
	"db" => new ConnectionHelper($em->getConnection()),
	"app" => new ApplicationHelper($app),)
	)
);
$cli->addCommands(array(
		new ListLinksCommand($app["shortener"]),
		new ServerCommand,
		new ShortenCommand($app["shortener"]),
		new FlushCacheCommand($app["cache_path"]),
		new CountByDaysCommand($app["country_service"]),
)
);
Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);

$cli->run();
