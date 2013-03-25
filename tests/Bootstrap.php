<?php
use Mparaiso\Provider\ConsoleServiceProvider;
use Mparaiso\Provider\UrlShortenerAppServiceProvider;
use Mparaiso\Provider\DoctrineORMServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

$autoload = require __DIR__."/../vendor/autoload.php";
!defined("ROOT") AND define("ROOT",__DIR__);

class Bootstrap{
    /**
     * @return Silex\Application
     */
    static function getApp(){
        $app  = new \Silex\Application;
        $app->register(new ConsoleServiceProvider);
        $app->register(new DoctrineServiceProvider,array(
            "db.options"=>array(
                "path"=>ROOT."/db.sqlite",
                "driver"=>"pdo_mysql"
            )
        ));
        $app->register(new DoctrineORMServiceProvider);
        $app->register(new UrlShortenerAppServiceProvider);
        return $app;

    }
}