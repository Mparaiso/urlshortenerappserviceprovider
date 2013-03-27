<?php

namespace Mparaiso\UrlShortener\Controller;

use Silex\ControllerProviderInterface;
use Mparaiso\Shortener\Controller\UrlShortenerController;
use Silex\WebTestCase;
use Silex\Application;

class UrlShortenerControllerTest extends WebTestCase
{

    function testConnect()
    {
        $this->assertInstanceOf('\Silex\ControllerProviderInterface', $this->app['url_shortener.controller']);
    }

    function testRedirect()
    {
    }

    function testInfo()
    {
        $this->markTestIncomplete("the method in the controller has not been implemented yet");
    }

    function testIndex()
    {
        $this->app['createDB']();
        $client   = $this->createClient();
        $crawler  = $client->request("GET", $this->app['url_generator']->generate('url_shortener_index'));
        $response = $client->getResponse();
        $this->assertNotNull($response);
        $this->assertTrue($response->isOk());
        #@note @silex tester l'envoi d'un formulaire
        $button  = $crawler->selectButton("Shorten");
        $form    = $button->form(array(
            'ShortenModel[original]' => 'http://yahoo.fr'
        ));
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isOk());
        /* @var $em \Doctrine\ORM\EntityManager */
        $em    = $this->app["orm.em"];
        $links = $em->getRepository('Mparaiso\Shortener\Entity\Link')->findAll();
        $this->assertTrue(count($links) === 1);
        $this->assertTrue($links[0]->getUrl()->getOriginal() === 'http://yahoo.fr');

        /*$crawler = $client->request("POST",$this->app['url_generator']->generate('url_shortener_index'),array(
            "ShortenModel"=>array(
            "original"=>"http://google.com",
            "custom"=>"goog"
            )
        ));*/
        $crawler->selectButton("Shorten");
        $form = $button->form(array(
            'ShortenModel[original]' => "http://google.com",
            'ShortenModel[custom]'   => "goog"
        ));
        $client->submit($form);
        $link = $em->getRepository('Mparaiso\Shortener\Entity\Link')->findOneBy(array('identifier' => 'goog'));
        $this->assertTrue($link != NULL);


    }

    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        return \Bootstrap::getApp();
    }
}