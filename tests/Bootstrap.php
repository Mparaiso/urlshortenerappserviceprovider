<?php
use Mparaiso\Provider\ConsoleServiceProvider;
use Mparaiso\Utils\Validator\CustomLoaderChain;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Validator\Mapping\Loader\LoaderChain;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
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
                "path" => 'url_shortener.sqlite',
            )
        ));
        $app->register(new DoctrineORMServiceProvider);
        $app->register(new UrlShortenerAppServiceProvider);
        $app->register(new ValidatorServiceProvider);
        /*$app->register(new ValidatorServiceProvider, array(
            "validator.mapping.chain_loader" => $app->share(function ($app) {
                return new CustomLoaderChain(
                    array(
                        new StaticMethodLoader,
                    )
                );
            }),
            "validator.mapping.class_metadata_factory" => $app->share(function ($app) {
                return new ClassMetadataFactory($app['validator.chain_loader']);
            })
        ));*/
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