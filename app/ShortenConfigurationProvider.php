<?php
use Silex\Application;

use Silex\ServiceProviderInterface;

use Shorten\Service\CountryService;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Configuration;

use Silex\Provider\UrlGeneratorServiceProvider;

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

use Silex\Provider\TranslationServiceProvider;

use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Mparaiso\Doctrine\ORM\Logger\MonologSQLLogger;
use Silex\Provider\MonologServiceProvider;
use Mparaiso\Provider\DoctrineORMServiceProvider;
use Shorten\DataAccessLayer\LinkDataProvider;
use Shorten\DataAccessLayer\UrlDataProvider;
use Shorten\DataAccessLayer\VisitDataProvider;
use Shorten\Service\ShortenerService;

class ShortenConfigurationProvider implements ServiceProviderInterface{
	function register(Application $app){

		$app["cache_path"]=__DIR__."/../cache/";


		$app->register(new TwigServiceProvider,array(
				"twig.path"=>__DIR__."/Shorten/Resources/views/",
				"twig.options"=>array("cache"=>__DIR__."/../cache/"),
				"twig.templates"=>$app["templates"],
		));

		$app->register(new ValidatorServiceProvider,array(
				"validator.mapping.class_metadata_factory"=>$app->share(function($app){
					$loader = new YamlFileLoader(__DIR__."/Shorten/Resources/validation/validation.yml");
					return new ClassMetadataFactory($loader);
				})
		));
		$app->register(new DoctrineORMServiceProvider,array(
				"em.logger"=>function($app){return new MonologSQLLogger($app["monolog"]);},
				"em.proxy_id"=>__DIR__."/../cache/",
				"em.options"=>array(
						"dbname"=>getenv("SYMFONY__SHORTEN__DBNAME"),
						"user"=>getenv("SYMFONY__SHORTEN__USER"),
						"password"=>getenv("SYMFONY__SHORTEN__PASSWORD"),
						"host"=>getenv("SYMFONY__SHORTEN__HOST"),
						"driver"=>"pdo_mysql"),
						"em.metadata"=>array("type"=>"yaml","path"=>array(__DIR__."/Shorten/Resources/doctrine/")),
		));
		

	}
	
	function boot(Application $app){}
}

