<?php

namespace App\Model;

/**
 * @Entity
 * @Table(name="Promotion")
 **/
class Promotion
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @Column(type="integer", nullable=false)
     **/
    protected $promotion;

    /**
     * @Column(type="date", nullable=false)
     **/
    protected $startingDate;

    /**
     * @Column(type="date", nullable=false)
     **/
    protected $endDate;

    public function getId()
    {
        return $this->id;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getStartingDate()
    {
        return $this->startingDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }
}