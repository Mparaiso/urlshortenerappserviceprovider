<?php

namespace Mparaiso\Shortener\Service;

use Psr\Log\NullLogger;
use Mparaiso\Shortener\Entity\Visit;
use Mparaiso\Shortener\Entity\Link;
use Mparaiso\Shortener\Entity\Url;
use Psr\Log\LoggerInterface;
use Guzzle\Http\Client;
use Doctrine\ORM\EntityManager;

class ShortenerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mparaiso\Shortener\Service\ShortenerService
     */
    public $shortenerService;
    public $app;

    function setUp(){
        $this->app = \Bootstrap::getApp();
        $this->shortenerService = new ShortenerService($this->app['orm.em']);
        $this->app['createDB']();
    }
    function testConstruct()
    {
        $this->assertInstanceOf('\Mparaiso\Shortener\Service\ShortenerService',$this->shortenerService);
    }

    //FR : raccourci une url
    function testShorten()
    {
        $links=array();
        $originals = array("http://yahoo.fr","http://google.fr");
        $links[0] = $this->shortenerService->shorten($originals[0]);
        $this->assertTrue($links[0]->getIsCustom()==false);
        $this->assertTrue($links[0]->getUrl()->getOriginal()==$originals[0]);
        //print_r($links[0]->getIdentifier());
        $links[1] = $this->shortenerService->shorten($originals[1],"goog");
        $this->assertEquals("goog",$links[1]->getIdentifier());

	}

    function testFindOneLinkByIdentifier()
    {
        $original = "http://facebook.com";
        $custom = "face";
        $identifier = $this->shortenerService->shorten($original)->getIdentifier();
        $link = $this->shortenerService->findOneLinkByIdentifier($identifier);
        $this->assertTrue($link!=null);
        $this->assertEquals($original,$link->getUrl()->getOriginal());
        $identifier = $this->shortenerService->shorten($original,$custom)->getIdentifier();
        $link = $this->shortenerService->findOneLinkByIdentifier($custom);
        $this->assertTrue($link!=null);
        $this->assertEquals($original,$link->getUrl()->getOriginal());
        $this->assertEquals($custom,$link->getIdentifier());
        $this->assertTrue($link->getIsCustom());
    }

    // FR : obtenir les liens
    function testFindAllLinks()
    {
        $this->shortenerService->shorten("http://yahoo.fr");
        $this->shortenerService->shorten("http://google.fr");
        $this->shortenerService->shorten("http://msn.fr");
        $this->shortenerService->shorten("http://facebook.fr");
        $links = $this->shortenerService->findAllLinks();
        $this->assertCount(4,$links);
    }

    //FR : incrémente le nombre de visites d'un lien
    function testAddVisit()
    {
        $link = $this->shortenerService->shorten("http://yahoo.fr");
        $this->shortenerService->addVisit($link,'87.248.120.148');
        $this->assertCount(1,$link->getVisits());
    }

    //FR : sauve une visite
    function TestSaveVisit()
    {
        $link = $this->shortenerService->shorten("http://yahoo.com");
        $visit = new Visit();
        $visit->setLink($link);
        $visit->setCountry("FR");
        $visit->setIp('87.248.120.148');
        $this->shortenerService->saveVisit($visit);
        $this->assertInternalType("int",$visit->getId());

    }

    // FR : sauve un lien
    function testSaveLink()
    {
        $link =$this->shortenerService->shorten("http://twitter.com","twitter");
        $link->setIdentifier("twittos");
        $link->setIsCustom(true);
        $this->shortenerService->saveLink($link);
        $this->assertNotNull($link->getId());
    }

    //FR : obtient le code d'un pays grace à une ip
    function testGetCountryFromIp()
    {
        $ip= '87.248.120.148';
        $country = $this->shortenerService->getCountryFromIp($ip);
        $this->assertEquals("XX",$country);

    }
}
