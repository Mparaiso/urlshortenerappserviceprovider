<?php

namespace Mparaiso\Provider;


class UrlShortenerAppServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    function setUp()
    {
        parent::setUp();
        $this->app = \Bootstrap::getApp();
    }

    function testRegister()
    {
        $this->assertTrue(isset($this->app['url_shortener.controller']));
    }
}