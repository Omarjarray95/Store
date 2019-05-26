<?php

namespace App\Model;

/**
 * @Entity
 * @Table(name="Category")
 **/
class Category
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $code;

    /**
     * @Column(type="string", nullable=true)
     **/
    protected $name;

    /**
     * @Column(type="string", nullable=true)
     **/
    protected $description;

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}