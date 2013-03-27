<?php

namespace Mparaiso\Provider;
use Mparaiso\Shortener\Controller\UrlShortenerController;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\LoaderChain;
use Mparaiso\Shortener\Service\CountryService;
use Mparaiso\Shortener\Command\ListLinksCommand;
use Mparaiso\Shortener\Command\ShortenCommand;
use Shorten\Command\CountByDaysCommand;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Mparaiso\Shortener\Service\ShortenerService;


/**
 * EN : provides a url shortener application
 * FR : fournit une application de raccourissement d'URLs
 * service provider dependencies :
 * TwigServiceProvider
 * FormServiceProvider
 * TranslationServiceProvider
 * ValidatorServiceProvider
 * UrlGeneratorServiceProvider
 * SessionServiceProvider
 * MonologServiceProvider (optional)
 */
class UrlShortenerAppServiceProvider implements ServiceProviderInterface
{

    protected $ns;

    function __construct($namespace = "url_shortener")
    {
        $this->ns = $namespace;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app["url_shortener.ns"]      = $ns = $this->ns;
        $app["$ns.controller"]        = $app->share(function ($app) use ($ns) {
            return new UrlShortenerController($app[$ns . '.shortener_service'], $app["url_shortener.ns"]);
        });
        $app["$ns.shortener_service"] = $app->share(function ($app) {
            return new ShortenerService($app['orm.em']);
        });
        $app["$ns.country_service"]   = $app->share(function ($app) {
            return new CountryService($app["orm.em"]);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registers
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        $app["orm.chain_driver"]->addDriver(new YamlDriver(__DIR__ . '/../Shortener/Resources/doctrine/'),
            'Mparaiso\Shortener\Entity');
        $templates = require __DIR__ . "/../Shortener/Resources/templates/templates.php";
        $twig_temp = $app['twig.templates'];
        foreach ($templates as $name => $value) {
            $twig_temp [$this->ns . '_' . $name] = $value;
        }
        $app['twig.templates'] = $twig_temp;
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $app["orm.em"];
        $em->getConfiguration()->addCustomDatetimeFunction("DATE", '\Mparaiso\Doctrine\ORM\Functions\Date');
        /* @var $cli \Symfony\Component\Console\Application */
        $cli = $app['console'];
        $cli->addCommands(array(
            new ShortenCommand,
            new ListLinksCommand,
        ));

//        $factory = $app['"validator.mapping.class_metadata_factory"'];
//        if(! $factory instanceof LoaderChain ){
//            $app['logger']->info('$app["validator.mapping.class_metadata_factory"] should be an instance of Symfony\Component\Validator\Mapping\Loader\LoaderChain');
//        }else{
//            /* @var $factory \Symfony\Component\Validator\Mapping\Loader\LoaderChain */
//            $factory->loadClassMetadata(new ClassMetadataFactory(new YamlFileLoader(__DIR__."/Shorten/Resources/validation/validation.yml")));
//        }

    }
}