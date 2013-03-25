<?php
namespace Mparaiso\Shortener\DataAccessLayer;


class VisitDataProvider {
	/**
	 * 
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	function getEm(){
		return $this->em;
	}
	function __construct(EntityManager $em) {
		$this->em = $em;
	}
	function save(Visit $visit) {
		$this->em->persist($visit);
		$this->em->flush();
	}
}
