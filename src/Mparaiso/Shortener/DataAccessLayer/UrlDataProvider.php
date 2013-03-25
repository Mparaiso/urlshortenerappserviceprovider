<?php
/**
 *
 * @author M.Paraiso
 *
 */
namespace Mparaiso\Shortener\DataAccessLayer;


use Doctrine\ORM\EntityManager;

class UrlDataProvider
{
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    function save(Url $url)
    {
        $this->em->persist($url);
        $this->em->flush();
    }

    function findOneBy(array $critera, $orderBy = NULL)
    {
        return $this->em
            ->getRepository('Entity\Shortener\Entity\Url')
            ->findOneBy($critera, $orderBy);
    }
}