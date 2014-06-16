<?php

namespace Cabinate\DAOBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cabinate\DAOBundle\Entity\ProductRepository")
 */
class Product extends Entity
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
     * @var float
     * @Expose
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="status", type="smallint",options={"comment"="0 normal,1 deleted"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="products")
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
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Product
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
     * @ORM\OneToMany(targetEntity="OrderPlan", mappedBy="product")
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
     * @return Product
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
     * @return Product
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;


    /**
     * Get Name
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="type", type="smallint",options={"comment"="0 food,1 drink"})
     */
    private $type;


    /**
     * Get type
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * @param integer
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}
