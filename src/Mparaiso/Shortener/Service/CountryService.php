<?php
namespace Mparaiso\Shortener\Service;

use Doctrine\ORM\EntityManager;

class CountryService {
	protected $em;
	function __construct(EntityManager $em){
		$this->em=$em;
	}
	function countDayBar($identifier,$num_of_days){
		
	}
	function countByDateWith($identifier,$num_of_days){
	    #@TODO fix me
	   /* $rawQuery = 'select date(v.createdAt)
				as date_created , count(v) as visit_count
				from Mparaiso\Shortener\Entity\Visit v
				where v.link.identifier = :identifier
				and v.createdAt between
				CURRENT_DATE()- :num_of_days
				and CURRENT_DATE()+1 group by date_create';

		$query = $this->em->createQuery($rawQuery);
		$query->setParameters(array("identifier"=>$identifier,
				"num_of_days"=>$num_of_days));
		return $query->getResult();*/
	}
}
