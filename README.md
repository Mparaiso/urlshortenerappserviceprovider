Url Shortener App Service provider
==================================

[![Build Status](https://travis-ci.org/Mparaiso/urlshortenerappserviceprovider.png?branch=master)](https://travis-ci.org/Mparaiso/urlshortenerappserviceprovider)

a url shortener service for your silex application
--------------------------------------------------

this provider provides your application a complete url shortener service
for your application , backed by doctrine ORM.

author : MParaiso

contact: mparaiso@online.fr

### Installation

database:

you can use the database database/db.sql or use the doctrine console

        php console.php orm:schema-tool:create

### Basic usage:

        use Mparaiso\Utils\Validator\CustomLoaderChain;
        $autoload = require __DIR__ . "/../vendor/autoload.php";
        !defined("ROOT") AND define("ROOT", __DIR__);
        $app = new \Silex\Application;
        $app['debug']=true;
        $app->register(new MonologServiceProvider, array('monolog.logfile' => ROOT.'/log.txt'));
        $app->register(new UrlGeneratorServiceProvider);
        $app->register(new ConsoleServiceProvider);
        $app->register(new SessionServiceProvider);
        $app->register(new TranslationServiceProvider);
        $app->register(new TwigServiceProvider);
        $app->register(new FormServiceProvider);
        $app->register(new DoctrineServiceProvider, array(
            "db.options" => array(
                "path"   => ROOT . "/db.sqlite",
                "driver" => "pdo_sqlite",
            )
        ));
        $app->register(new DoctrineORMServiceProvider);
        $app->register(new UrlShortenerAppServiceProvider);
        $app->mount("/", $app['url_shortener.controller']));


### Basic API

+ GET "/" : url shortener form
+ POST "/" : create a new short url
+ GET "/{identifier}" : redirect to an shortened url according to its identifier

You can change the root route by mounting  ```$app['url_shortener.controller']``` to a custom route