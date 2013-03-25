<?php
namespace Shorten\Service;
use Shorten\DataAccessLayer\VisitDataProvider;

class CountryService {
	/**
	 * @var \Shorten\DataAccessLayer\VisitDataProvider
	 */
	protected $vpd;
	function __construct(VisitDataProvider $vdp){
		$this->vpd=$vdp;
	}
	function countDayBar($identifier,$num_of_days){
		
	}
	function countByDateWith($identifier,$num_of_days){
		$em = $this->vpd->getEm();
		$query = $em->createQuery("select date(v.createdAt) 
				as date_created , count(v) as visit_count 
				from Shorten\Entity\Visit v 
				where v.link.identifier = :identifier 
				and v.createdAt between 
				CURRENT_DATE()- :num_of_days 
				and CURRENT_DATE()+1 group by date_created ");
		$query->setParameters(array("identifier"=>$identifier,
				"num_of_days"=>$num_of_days));
		$result = $query->getResult();
		return $result->fetchAll();
	}
}
