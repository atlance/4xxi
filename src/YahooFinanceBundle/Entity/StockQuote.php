<?php

namespace YahooFinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class StockQuote.
 *
 * @ORM\Entity
 * @ORM\Table(name="stock_quotes", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="search_idx", columns={"symbol"})
 * })
 * @UniqueEntity(fields={"symbol"})
 */
class StockQuote
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
     *     unique=true,
     *     options={"comment":"Тикер"}
     * )
     */
    protected $symbol;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Company",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="StockExchange",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="stock_exchange_id", referencedColumnName="id")
     */
    protected $stockExchange;

    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
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
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return StockExchange
     */
    public function getStockExchange()
    {
        return $this->stockExchange;
    }

    /**
     * @param StockExchange $stockExchange
     */
    public function setStockExchange(StockExchange $stockExchange)
    {
        $this->stockExchange = $stockExchange;
    }
}
