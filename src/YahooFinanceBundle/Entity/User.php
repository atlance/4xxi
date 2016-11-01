<?php

namespace YahooFinanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Portfolio",
     *     mappedBy="user",
     *     cascade={"persist"}
     * )
     */
    protected $portfolios;

    public function __construct()
    {
        parent::__construct();
        $this->portfolios = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getPortfolios()
    {
        return $this->portfolios;
    }

    /**
     * @param Portfolio $portfolio
     */
    public function setPortfolios(Portfolio $portfolio)
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
        }
    }
}
