<?php

namespace App\Model;

/**
 * @Entity
 * @Table(name="Product")
 **/
class Product
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $reference;

    /**
     * @Column(type="string", nullable=false)
     **/
    protected $designation;

    /**
     * @Column(type="integer", nullable=true)
     **/
    protected $quantity;

    /**
     * @Column(type="float", nullable=true)
     **/
    protected $price;

    /**
     * @Column(type="string", nullable=true)
     **/
    protected $photo;

    /**
     * @Column(type="boolean", nullable=false)
     **/
    protected $available = false;

    /**
     * @ManyToOne(targetEntity="Promotion", fetch="EAGER")
     * @JoinColumn(name="promotion", referencedColumnName="id")
     **/
    protected $inPromotion = null;

    /**
     * @ManyToOne(targetEntity="Category", fetch="EAGER")
     * @JoinColumn(name="category", referencedColumnName="code")
     **/
    protected $category;


    public function getReference()
    {
        return $this->reference;
    }

    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDesignation()
    {
        return $this->designation;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function setInPromotion(\App\Model\Promotion $promotion = null)
    {
        $this->inPromotion = $promotion;

        return $this;
    }

    public function getInPromotion()
    {
        return $this->inPromotion;
    }

    public function setCategory(\App\Model\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }
}