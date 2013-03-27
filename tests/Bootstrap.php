<?php
use Mparaiso\Provider\ConsoleServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Mparaiso\Shortener\Controller\UrlShortenerController;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Doctrine\ORM\Tools\SchemaTool;
use Mparaiso\Provider\UrlShortenerAppServiceProvider;
use Mparaiso\Provider\DoctrineORMServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

error_reporting(E_ALL);
$autoload = require __DIR__ . "/../vendor/autoload.php";
!defined("ROOT") AND define("ROOT", __DIR__);

class Bootstrap
{
    /**
     * @return Silex\Application
     */
    static function getApp()
    {
        $app          = new \Silex\Application;
        $app['debug'] = TRUE;
        $app->register(new MonologServiceProvider, array('monolog.logfile' => ROOT . '/log.txt'));
        $app->register(new UrlGeneratorServiceProvider);
        $app->register(new ConsoleServiceProvider);
        $app->register(new SessionServiceProvider, array('session.test' => TRUE));
        $app->register(new TranslationServiceProvider);
        $app->register(new TwigServiceProvider, array("twig.options" => array("path" => ROOT)));
        $app->register(new FormServiceProvider);
        $app->register(new DoctrineServiceProvider, array(
            "db.options" => array(
                "driver" => "pdo_sqlite",
                "memory" => TRUE,
            )
        ));
        $app->register(new DoctrineORMServiceProvider);
        $app->register(new UrlShortenerAppServiceProvider);
        $app->mount("/", $app['url_shortener.controller']);
        $app->boot();
        /**
         * create the database
         */
        /* @var $em \Doctrine\ORM\EntityManager */
        $em              = $app['orm.em'];
        $app['createDB'] = $app->protect(function () use ($em) {
            $tool = new SchemaTool($em);
            $tool->dropDatabase();
            $tool->createSchema($em->getMetadataFactory()->getAllMetadata());
        });
        return $app;

    }
}