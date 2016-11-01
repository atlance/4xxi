<?php

namespace YahooFinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Company.
 *
 * @ORM\Entity
 * @ORM\Table(name="companies", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="search_idx", columns={"name"})}
 * )
 * @UniqueEntity(fields={"name"})
 */
class Company
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
     *     options={"comment":"Название"}
     * )
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
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
}
