<?php

namespace SecretBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="SecretBundle\Repository\CartRepository")
 */
class Cart
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
     * @ORM\Column(name="cartName", type="string", length=255)
     */
    private $cartName;

    /**
     * @ORM\OneToOne(targetEntity="Product", mappedBy="cart", cascade={"persist", "merge"})
     */
    private $product;


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
     * Set cartName
     *
     * @param string $cartName
     *
     * @return Cart
     */
    public function setCartName($cartName)
    {
        $this->cartName = $cartName;

        return $this;
    }

    /**
     * Get cartName
     *
     * @return string
     */
    public function getCartName()
    {
        return $this->cartName;
    }

    /**
     * Set product
     *
     * @param \SecretBundle\Entity\Product $product
     *
     * @return Cart
     */
    public function setProduct(\SecretBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \SecretBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
