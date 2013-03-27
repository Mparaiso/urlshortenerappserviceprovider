<?php

namespace Mparaiso\Shortener\Service;

use Psr\Log\NullLogger;
use Mparaiso\Shortener\Entity\Visit;
use Mparaiso\Shortener\Entity\Link;
use Mparaiso\Shortener\Entity\Url;
use Psr\Log\LoggerInterface;
use Guzzle\Http\Client;
use Doctrine\ORM\EntityManager;

class ShortenerService
{

    protected $logger;
    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    //FR : raccourci une url
    function shorten($original, $custom = NULL)
    {
	    $url = $this->em->getRepository('Mparaiso\Shortener\Entity\Url')->findOneBy(array("original" => $original));
		if ($url == NULL) {
            $url = new Url();
            $url->setOriginal($original);
            $this->em->persist($url);
            $this->em->flush();
        }
		if ($custom != NULL) {
            // FR : si le link custom existe déja , le retourner
            $link = $this->em->getRepository('Mparaiso\Shortener\Entity\Link')
                ->findOneBy(
                array("identifier" => $custom, "is_custom" => TRUE,
                      'url'        => $url));
            if ($link != NULL)
                return $link;
            $identifier = $custom;
            $isCustom   = TRUE;
        } else {
            // FR : base 36 encoding
            $identifier = base_convert($url->getId(), 10, 36);
            $isCustom   = FALSE;
        }
		$link = new Link();
		$link->setIsCustom($isCustom);
		$link->setIdentifier($identifier);
		$link->setCreatedAt(new \DateTime);
		$link->setUrl($url);
		 $this->em->persist($link);
		 $this->em->flush();
		 return $link;
	}

    /**
     * @param $identifier
     * @return Link
     */
    function findOneLinkByIdentifier($identifier)
    {
        return $this->em->getRepository('Mparaiso\Shortener\Entity\Link')->findOneBy(array('identifier' => $identifier));
    }

    // FR : obtenir les liens
    function findAllLinks(array $criteria = array(), $orderBy = NULL, $limit = NULL,
                          $offset = NULL)
    {
        return $this->em->getRepository('Mparaiso\Shortener\Entity\Link')->findBy($criteria, $orderBy, $limit, $offset);
    }

    //FR : incrémente le nombre de visites d'un lien
    function addVisit(Link $link, $ip)
    {
        $visit = new Visit;
        $visit->setIp($ip);
        $visit->setCreatedAt(new \DateTime);
        $visit->setCountry($this->getCountryFromIp($ip));
        $link->addVisit($visit);
        $this->em->persist($visit);
        $this->em->flush();
        return $link;
    }

    function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    function getLogger()
    {
        if (!isset($this->logger)) {
            $this->logger = new NullLogger();
        }
        return $this->logger;
    }

    //FR : sauve une visite
    function saveVisit(Visit $visit)
    {
        $this->em->persist($visit);
        $this->em->flush();
        return $visit;
    }

    // FR : sauve un lien
    function saveLink(Link $link)
    {
        $this->em->persist($link);
        $this->em->flush();
        return $link;
    }

    //FR : obtient le code d'un pays grace à une ip
    function getCountryFromIp($ip)
    {
        try {
            $client       = new Client("http://api.hostip.info/?ip={{ip}}", array("ip" => $ip));
            $responseBody = $client->get()->send()->getBody(TRUE);
            $xml          = new \DOMDocument();
            $xml->loadXML($responseBody);
            $country = $xml->getElementsByTagName("countryAbbrev")->item(0)->nodeValue;
        } catch (\Exception $e) {
            $this->getLogger()->warning($e->getMessage());
            $country = "XX";
        }
        return $country;
    }
}
