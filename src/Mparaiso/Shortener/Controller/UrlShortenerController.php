<?php

namespace Mparaiso\UrlShortener\Controller;

use Silex\ControllerProviderInterface;
use Mparaiso\UrlShortener\Form\ShortenModelType;
use Mparaiso\UrlShortener\Model\ShortenModel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class UrlShortenerController implements ControllerProviderInterface
{
    protected $ns;

    function __construct($namespace="url_shortener")
    {
       $this->ns = $namespace;
    }

    function connect(Application $app)
    {
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app["controllers_factory"];
        $controllers->match("/", array($this, "_index"))->bind("index");
        $controllers->match("/info/{identifier}", array($this, "_info"))->bind("info");
        $controllers->match("/{identifier}", array($this, "_redirect"))->bind("redirect");
        return $controllers;
    }

    function _redirect(Request $req, $identifier, Application $app)
    {
        /* FR : redirige vers le lien complet */
        /* @var $link \Shorten\Entity\Link */
        $link = $app["$this->ns.shortener"]->findOneLinkByIdentifier($identifier);
        if (NULL != $link) {
            $ip  = $req->getClientIp();
            $uri = $req->getRequestUri();
            $app["logger"]->info("user ip requiring $uri is $ip ");
            $app["$this->ns.shortener"]->addVisit($link, $ip);
            return $app->redirect($link->getUrl()->getOriginal());
        } else {
            $app["session"]->getFlashBag()->add("error", "Cant find shortened link with identifier $identifier");
            return $app->redirect($app["url_generator"]->generate("index"));
        }
    }

    function _info(Request $request, $identifier, Application $app)
    {
        $link = $app["$this->ns.shortener"]->findOneLinkByIdentifier($identifier);
        if ($link === NULL) {
            return $app->redirect($app["url_generator"]->generate("index"));
        } else {
            return $app["twig"]->render("info", array("link" => $link));
        }
    }

    function _index(Request $req, Application $app)
    {
        $shortenModel = new ShortenModel();
        $link         = NULL;
        $form         = $app["form.factory"]->create(new ShortenModelType, $shortenModel);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app["session"]->getFlashBag()->add("succes", "Url shortened!");
                $shortener = $app["$this->ns.shortener"];
                $link      = $shortener->shorten($shortenModel->getOriginal(), $shortenModel->getCustom());
            }
        }
        return $app["twig"]->render("index", array("form" => $form->createView(), "link" => $link));
    }
}