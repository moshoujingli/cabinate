<?php

namespace Cabinate\DAOBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\ORM\Mapping as ORM;

/**
 * TableUnit
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cabinate\DAOBundle\Entity\TableUnitRepository")
 */
class TableUnit extends Entity
{
    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="tableUnits")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     */
    protected $restaurant;

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
     * Set status
     *
     * @param integer $status
     * @return TableUnit
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\OneToMany(targetEntity="OrderPlan", mappedBy="tableUnit")
     */
    protected $orderPlan;

    public function __construct()
    {
        $this->orderPlan = new ArrayCollection();
    }

    /**
     * Set restaurant
     *
     * @param \Cabinate\DAOBundle\Entity\Restaurant $restaurant
     * @return TableUnit
     */
    public function setRestaurant(\Cabinate\DAOBundle\Entity\Restaurant $restaurant = null)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get restaurant
     *
     * @return \Cabinate\DAOBundle\Entity\Restaurant 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Add orderPlan
     *
     * @param \Cabinate\DAOBundle\Entity\OrderPlan $orderPlan
     * @return TableUnit
     */
    public function addOrderPlan(\Cabinate\DAOBundle\Entity\OrderPlan $orderPlan)
    {
        $this->orderPlan[] = $orderPlan;

        return $this;
    }

    /**
     * Remove orderPlan
     *
     * @param \Cabinate\DAOBundle\Entity\OrderPlan $orderPlan
     */
    public function removeOrderPlan(\Cabinate\DAOBundle\Entity\OrderPlan $orderPlan)
    {
        $this->orderPlan->removeElement($orderPlan);
    }

    /**
     * Get orderPlan
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderPlan()
    {
        return $this->orderPlan;
    }
    /**
     * @var string
     * @Expose
     * @ORM\Column(name="table_key", type="string", length=40)
     */
    private $tableKey;


    /**
     * Get TableKey
     *
     * @return string
     */
    public function getTableKey()
    {
        return $this->tableKey;
    }

    /**
     * Set TableKey
     * @param string
     */
    public function setTableKey($tableKey)
    {
        $this->tableKey = $tableKey;
    }
}
