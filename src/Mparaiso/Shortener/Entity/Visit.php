<?php

namespace Mparaiso\Shortener\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 */
class Visit
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $country;

    /**
     * @var \Shorten\Entity\Link
     */
    private $link;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Visit
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Visit
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Visit
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set link
     *
     * @param Link $link
     * @return Visit
     */
    public function setLink(Link $link = null)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
    }
}