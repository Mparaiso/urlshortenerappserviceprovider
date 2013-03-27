<?php

namespace Mparaiso\Shortener\Controller;

use Silex\ControllerProviderInterface;
use Mparaiso\Shortener\Service\ShortenerService;
use Mparaiso\Shortener\Form\ShortenModelType;
use Mparaiso\Shortener\Model\ShortenModel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class UrlShortenerController implements ControllerProviderInterface
{
    protected $ns;
    protected $service;

    function __construct(ShortenerService $shortenerService,
                         $namespace = "url_shortener")
    {
        $this->service = $shortenerService;
        $this->ns      = $namespace;
    }

    function connect(Application $app)
    {
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app["controllers_factory"];
        $controllers->match("/", array($this, "_index"))->bind($this->ns."_index");
        #@TODO fix info path
        #$controllers->match("/info/{identifier}", array($this, "_info"))->bind($this->ns."_info");
        $controllers->match("/{identifier}", array($this, "_redirect"))->bind($this->ns."_redirect");
        return $controllers;
    }

    function _redirect(Request $req, $identifier, Application $app)
    {
        /* FR : redirige vers le lien complet */
        $link = $this->service->findOneLinkByIdentifier($identifier);
        if (NULL != $link) {
            $ip  = $req->getClientIp();
            $uri = $req->getRequestUri();
            $app["logger"]->info("user ip requiring $uri is $ip ");
            $this->service->addVisit($link, $ip);
            return $app->redirect($link->getUrl()->getOriginal());
        } else {
            $app["session"]->getFlashBag()->add("error", "Cant find shortened link with identifier $identifier");
            return $app->redirect($app["url_generator"]->generate($this->ns."_index"));
        }
    }

    function _info(Request $request, $identifier, Application $app)
    {
        $link = $this->service->findOneLinkByIdentifier($identifier);
        if ($link === NULL) {
            return $app->redirect($app["url_generator"]->generate($this->ns."_index"));
        } else {
            return $app["twig"]->render($this->ns."_info", array("link" => $link));
        }
    }

    function _index(Request $req, Application $app)
    {
        $shortenModel = new ShortenModel();
        $link         = NULL;
        /* @var $form \Symfony\Component\Form\Form */
        $form         = $app["form.factory"]->create(new ShortenModelType, $shortenModel);
        if ("POST" === $req->getMethod()) {
            $app['monolog']->addInfo(print_r($req->request->all(),true));
            $form->bind($req);
            if ($form->isValid()) {
                $app["session"]->getFlashBag()->add("succes", "Url shortened!");

                $link = $this->service->shorten($shortenModel->getOriginal(), $shortenModel->getCustom());
            }
        }
        return $app["twig"]->render($this->ns."_index", array("form" => $form->createView(), "link" => $link));
    }
}