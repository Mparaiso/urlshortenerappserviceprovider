<?php 

namespace Mparaiso\Provider;
use Mparaiso\UrlShortener\Controller\UrlShortenerController;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Shorten\Service\CountryService;
use Mparaiso\Shortener\Service\ShortenerService;
use Mparaiso\Shortener\DataAccessLayer\VisitDataProvider;
use Mparaiso\Shortener\DataAccessLayer\UrlDataProvider;
use Mparaiso\Shortener\DataAccessLayer\LinkDataProvider;


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
class UrlShortenerAppServiceProvider implements ServiceProviderInterface{

    protected $ns;

    function __construct($namespace = "urlshortener")
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
        $ns=$this->ns;
        $app["url_shortener.namespace"]=$ns;
        $app["$ns.templates"]= $app->share(function($app){
            return require(__DIR__ . "/../Resources/templates/templates.php");
        });
        $app["$ns.controller"]=$app->share(function()use($ns){
            new UrlShortenerController($ns);
        });
        $app["$ns.link_provider"]=$app->share(function($app){
            return new LinkDataProvider($app["em"]);
        });
        $app["$ns.url_provider"]=$app->share(function($app){
            return new UrlDataProvider($app["em"]);
        });
        $app["$ns.visit_provider"]=$app->share(function($app){
            return new VisitDataProvider($app["em"]);
        });
        $app["$ns.shortener"]=$app->share(function($app)use($ns){
            return new ShortenerService($app["$ns.link_provider"],$app["$ns.url_provider"],$app["$ns.visit_provider"]);
        });
        $app["$ns.country_service"]=$app->share(function($app)use($ns){
            return new CountryService($app["$ns.visit_provider"]);
        });
        $app["orm.driver.configs"]=$app->extend("orm.driver.configs",function($configs,$app){
            $configs[]=array(
                "type"=>"yaml",
                "namespace"=>'Mparaiso\Shortener\Entity',
                "paths"=>array(__DIR__.'/../Shortener/Resources/doctrine/'),
            );
            return $configs;
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
        // TODO: Implement boot() method.
    }
}