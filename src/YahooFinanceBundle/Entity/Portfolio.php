<?php

namespace YahooFinanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Portfolio.
 *
 * @ORM\Entity
 * @ORM\Table(name="portfolios")
 * @ORM\HasLifecycleCallbacks
 */
class Portfolio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *     type="string",
     *     nullable=false,
     *     options={"comment":"Название"}
     * )
     */
    protected $name;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="User",
     *     inversedBy="portfolios"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Serializer\Exclude()
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(
     *     type="datetime",
     *     nullable=false,
     *     options={"comment":"Дата создания"}
     * )
     * @Serializer\Exclude()
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(
     *     type="datetime",
     *     nullable=false,
     *     options={"comment":"Дата обновления"}
     * )
     * @Serializer\Exclude()
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="StockQuote")
     * @ORM\JoinTable(name="portfolio_stock_quote",
     *     joinColumns={@ORM\JoinColumn(name="portfolio_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="stock_quote_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection
     */
    protected $stockQuotes;

    public function __construct()
    {
        $this->stockQuotes = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getStockQuotes()
    {
        return $this->stockQuotes;
    }

    /**
     * @return array
     */
    public function getStockSymbols()
    {
        $symbols = $this->stockQuotes->map(function (StockQuote $stockQuote) {
            return $stockQuote->getSymbol();
        })->toArray();

        return $symbols;
    }

    /**
     * @param StockQuote $stockQuote
     */
    public function setStockQuote(StockQuote $stockQuote)
    {
        if (!$this->stockQuotes->contains($stockQuote)) {
            $this->stockQuotes->add($stockQuote);
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return Portfolio
     */
    public function setCreatedAt(\DateTime $dateTime)
    {
        $this->createdAt = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return Portfolio
     */
    public function setUpdatedAt(\DateTime $dateTime)
    {
        $this->updatedAt = $dateTime;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setDateTime()
    {
        $dateTime = new \DateTime();

        $this->setUpdatedAt($dateTime);

        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt($dateTime);
        }
    }
}
