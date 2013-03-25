<?php

namespace Mparaiso\Shortener\Service;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Shorten\Entity\Visit;
use Guzzle\Http\Client;
use Shorten\DataAccessLayer\VisitDataProvider;
use Shorten\DataAccessLayer\UrlDataProvider;
use Shorten\DataAccessLayer\LinkDataProvider;
use Shorten\Entity\Url;
use Shorten\Entity\Link;
use Doctrine\ORM\EntityManager;
use Shorten\Entity\LinkRepository;

class ShortenerService {
	
	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;
	protected $ldp;
	protected $udp;
	protected $vdp;
	
	function __construct(LinkDataProvider $ldp, UrlDataProvider $udp,
			VisitDataProvider $vdp) {
		$this->ldp = $ldp;
		$this->udp = $udp;
		$this->vdp = $vdp;
	}

	/**
	 * FR : raccourci une url
	 * @param string $original
	 * @param string $custom
	 * @return \Shorten\Entity\Link
	 */
	function shorten($original, $custom = null) {
		$url = $this->udp->findOneBy(array("original" => $original));
		if ($url == null) {
			$url = new Url();
			$url->setOriginal($original);
			$this->udp->save($url);
		}
		if ($custom != null) {
			// FR : si le link custom existe d�ja , le retourner
			/* @var $link \Shorten\Entity\Link  */
			$link = $this->ldp
					->findOneBy(
							array("identifier" => $custom, "is_custom" => true,
									'url' => $url));
			if ($link != null)
				return $link;
			$identifier = $custom;
			$isCustom = true;
		} else {
			// FR : base 36 encoding
			$identifier = base_convert($url->getId(), 10, 36);
			$isCustom = false;
		}
		/* @var $link \Shorten\Entity\Link  */
		$link = new Link();
		$link->setIsCustom($isCustom);
		$link->setIdentifier($identifier);
		$link->setCreatedAt(new \DateTime);
		$link->setUrl($url);
		return $this->ldp->save($link);
	}
	function findOneLinkByIdentifier($identifier) {
		return $this->ldp->findOneByIdentifier($identifier);
	}
	/**
	 * FR : obtenir les liens
	 * @param array $criteral
	 * @param array $orderBy
	 * @param number $limit
	 * @param number $offset
	 */
	function findAllLinks($criteral = null, $orderBy = null, $limit = null,
			$offset = null) {
		return $this->ldp->findAll($criteral, $orderBy, $limit, $offset);
	}
	/**
	 * FR : incr�mente le nombre de visites d'un line
	 * @param Link $link
	 * @param unknown $ip
	 * @return \Shorten\Entity\Link
	 */
	function addVisit(Link $link, $ip) {
		$visit = new Visit;
		$visit->setIp($ip);
		$visit->setCreatedAt(new \DateTime);
		$visit->setCountry($this->getCountryFromIp($ip));
		$visit->setLink($link);
		$this->saveVisit($visit);
		return $link;
	}
	function setLogger(LoggerInterface $logger){
		$this->logger = $logger;
	}
	function getLogger(){
		if (!isset($this->logger)){
			$this->logger = new \NullLogger;
		}
		return $this->logger;
	}
	/**
	 * FR : sauve une visite
	 * @param Visit $visit
	 */
	function saveVisit(Visit $visit){
		$this->vdp->save($visit);
	}
	/**
	 * FR : sauve un lien
	 * @param Link $link
	 * @return \Shorten\Entity\Link
	 */
	function saveLink(Link $link) {
		$this->ldp->save($link);
		return $link;
	}
	/**
	 * FR : obtient le code d'un pays grace � une ip
	 * @param string $ip
	 * @return string
	 */
	function getCountryFromIp($ip) {
		try {
			$client = new Client("http://api.hostip.info/?ip={{ip}}",array("ip" => $ip));
			$responseBody = $client->get()->send()->getBody(true);
			$xml = new \DOMDocument();
			$xml->loadXML($responseBody);
			$country = $xml->getElementsByTagName("countryAbbrev")->item(0)->nodeValue;
		} catch (Exception $e) {
			$this->getLogger()->emergency($e->getMessage());
			$country = "XX";
		}
		return $country;
	}
}
