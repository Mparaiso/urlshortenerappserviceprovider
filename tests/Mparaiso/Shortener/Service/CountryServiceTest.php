<?php
namespace Shorten\Service;

use Doctrine\ORM\EntityManager;
use Mparaiso\Shortener\Service\ShortenerService;
use Mparaiso\Shortener\Service\CountryService;

class CountryServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Silex\Application
     */
    public $app;
    /**
     * @var CountryService
     */
    public $service;
    /**
     * @var ShortenerService
     */
    public $shortener;

    function setUp()
    {
        $this->app       = \Bootstrap::getApp();
        $this->service   = new CountryService($this->app["orm.em"]);
        $this->shortener = new ShortenerService($this->app['orm.em']);
        $this->app['createDB']();
    }

    function testConstruct()
    {
        $this->assertNotNull($this->service);
    }

    function testCountDayBar()
    {
        $this->markTestIncomplete("implement that test");
    }

    function testCountByDateWith()
    {
        $this->markTestIncomplete("implement that test");
        /* $link = $this->shortener->shorten("http://yahoo.fr","yahoo");
         $this->shortener->addVisit($link,'192.168.1.171');
         $this->shortener->addVisit($link,'192.168.1.171');
         $this->shortener->addVisit($link,'192.168.1.171');
         $result = $this->service->countByDateWith('yahoo',3);
         print_r($result);
 //        $this->assertEquals(3,$result[0]['visit_count']);*/

    }
}
