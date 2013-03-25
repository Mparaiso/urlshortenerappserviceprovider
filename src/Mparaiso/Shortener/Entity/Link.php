<?php

namespace Mparaiso\Shortener\Entity;


/**
 * Link
 */
class Link
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $is_custom;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $visits;

    /**
     * @var \Shorten\Entity\Url
     */
    private $url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visits = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set is_custom
     *
     * @param boolean $isCustom
     * @return Link
     */
    public function setIsCustom($isCustom)
    {
        $this->is_custom = $isCustom;
    
        return $this;
    }

    /**
     * Get is_custom
     *
     * @return boolean 
     */
    public function getIsCustom()
    {
        return $this->is_custom;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Link
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Link
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
     * Add visits
     *
     * @param \Shorten\Entity\Visit $visits
     * @return Link
     */
    public function addVisit(\Shorten\Entity\Visit $visits)
    {
        $this->visits[] = $visits;
    
        return $this;
    }

    /**
     * Remove visits
     *
     * @param \Shorten\Entity\Visit $visits
     */
    public function removeVisit(\Shorten\Entity\Visit $visits)
    {
        $this->visits->removeElement($visits);
    }

    /**
     * Get visits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set url
     *
     * @param \Shorten\Entity\Url $url
     * @return Link
     */
    public function setUrl(\Shorten\Entity\Url $url = null)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return \Shorten\Entity\Url 
     */
    public function getUrl()
    {
        return $this->url;
    }
}