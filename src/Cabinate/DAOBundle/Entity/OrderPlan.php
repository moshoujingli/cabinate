<?php

namespace Cabinate\DAOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderPlan
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cabinate\DAOBundle\Entity\OrderPlanRepository")
 */
class OrderPlan extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TableUnit", inversedBy="orderPlan")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id")
     */
    protected $tableUnit;
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderPlan")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="trade_session_id", type="integer")
     */
    private $tradeSessionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime")
     */
    private $createdTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_time", type="datetime")
     */
    private $updatedTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="served_time", type="datetime")
     */
    private $servedTime;


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
     * @return OrderPlan
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
     * Set tradeSessionId
     *
     * @param integer $tradeSessionId
     * @return OrderPlan
     */
    public function setTradeSessionId($tradeSessionId)
    {
        $this->tradeSessionId = $tradeSessionId;

        return $this;
    }

    /**
     * Get tradeSessionId
     *
     * @return integer 
     */
    public function getTradeSessionId()
    {
        return $this->tradeSessionId;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return OrderPlan
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     * @return OrderPlan
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;

        return $this;
    }

    /**
     * Get updatedTime
     *
     * @return \DateTime 
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Set servedTime
     *
     * @param \DateTime $servedTime
     * @return OrderPlan
     */
    public function setServedTime($servedTime)
    {
        $this->servedTime = $servedTime;

        return $this;
    }

    /**
     * Get servedTime
     *
     * @return \DateTime 
     */
    public function getServedTime()
    {
        return $this->servedTime;
    }

    /**
     * Set tableUnit
     *
     * @param \Cabinate\DAOBundle\Entity\TableUnit $tableUnit
     * @return OrderPlan
     */
    public function setTableUnit(\Cabinate\DAOBundle\Entity\TableUnit $tableUnit = null)
    {
        $this->tableUnit = $tableUnit;

        return $this;
    }

    /**
     * Get tableUnit
     *
     * @return \Cabinate\DAOBundle\Entity\TableUnit 
     */
    public function getTableUnit()
    {
        return $this->tableUnit;
    }

    /**
     * Set product
     *
     * @param \Cabinate\DAOBundle\Entity\Product $product
     * @return OrderPlan
     */
    public function setProduct(\Cabinate\DAOBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Cabinate\DAOBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
