<?php

namespace Mparaiso\Shortener\DataAccessLayer;


class LinkDataProvider{
	protected $em;
	protected $entityName;
	function __construct(EntityManager $em, $entityName="Shorten\Entity\Link"){
		$this->em = $em;
		$this->entityName = $entityName;
	}

	function save(Link $link){
		if($link->getCreatedAt()==null){
			$link->setCreatedAt(new \DateTime);
		}

		$this->em->persist($link);
		$this->em->flush();
		return $link;
	}
	
	function findOneByIdentifier($identifier){
		return $this->em->getRepository($this->entityName)->findOneByIdentifier($identifier);
	}
	
	function findAll($critera=null,array $orderBy=null,$limit=null,$offset=null){
		if($critera==null)$critera=array();
		return $this->em
			->getRepository($this->entityName)
			->findBy($critera,$orderBy,$limit,$offset);
	}
	
	function findOneBy($critera,$orderyBy=null) {
		return $this->em
			->getRepository($this->entityName)
			->findOneBy($critera,$orderyBy);
	}
}