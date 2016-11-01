<?php

namespace YahooFinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class StockExchange.
 *
 * @ORM\Entity
 * @ORM\Table(name="stock_exchanges", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="search_idx", columns={"code"})
 * })
 * @UniqueEntity(fields={"code"})
 */
class StockExchange
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
     *     options={"comment":"Код биржи"}
     * )
     */
    protected $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }
}
