<?php

namespace Cabinate\DAOBundle\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\ORM\Mapping as ORM;

/**
 * Restaurant
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cabinate\DAOBundle\Entity\RestaurantRepository")
 */
class Restaurant extends Entity
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="restaurant")
     */
    protected $products;
    /**
     * @ORM\OneToMany(targetEntity="TableUnit", mappedBy="restaurant")
     */
    protected $tableUnits;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->tableUnits = new ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \Cabinate\DAOBundle\Entity\Product $products
     * @return Restaurant
     */
    public function addProduct(\Cabinate\DAOBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Cabinate\DAOBundle\Entity\Product $products
     */
    public function removeProduct(\Cabinate\DAOBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add tableUnits
     *
     * @param \Cabinate\DAOBundle\Entity\TableUnit $tableUnits
     * @return Restaurant
     */
    public function addTableUnit(\Cabinate\DAOBundle\Entity\TableUnit $tableUnits)
    {
        $this->tableUnits[] = $tableUnits;

        return $this;
    }

    /**
     * Remove tableUnits
     *
     * @param \Cabinate\DAOBundle\Entity\TableUnit $tableUnits
     */
    public function removeTableUnit(\Cabinate\DAOBundle\Entity\TableUnit $tableUnits)
    {
        $this->tableUnits->removeElement($tableUnits);
    }

    /**
     * Get tableUnits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTableUnits()
    {
        return $this->tableUnits;
    }

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;


    /**
     * Get Name
     *
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
}
