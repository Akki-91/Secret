<?php

namespace SecretBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="SecretBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="ammountAvaible", type="integer")
     */
    private $ammountAvaible;

    /**
     * @ORM\OneToOne(targetEntity="Cart", inversedBy="product")
     * @ORM\JoinColumn(name="cartId", referencedColumnName="id")
     */
    private $cart;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ammountAvaible
     *
     * @param integer $ammountAvaible
     *
     * @return Product
     */
    public function setAmmountAvaible($ammountAvaible)
    {
        $this->ammountAvaible = $ammountAvaible;

        return $this;
    }

    /**
     * Get ammountAvaible
     *
     * @return int
     */
    public function getAmmountAvaible()
    {
        return $this->ammountAvaible;
    }

    /**
     * Set cart
     *
     * @param \SecretBundle\Entity\Cart $cart
     *
     * @return Product
     */
    public function setCart(\SecretBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \SecretBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
    }
}
