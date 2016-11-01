<?php

namespace YahooFinanceBundle\Subscribers\ORM;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use YahooFinanceBundle\Entity\Portfolio;
use YahooFinanceBundle\Entity\StockQuote;

class PortfolioSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(Portfolio $portfolio, LifecycleEventArgs $event)
    {
        $portfolio->setUser($this->tokenStorage->getToken()->getUser());
        $stockQuotes = $portfolio->getStockQuotes();

        /** @var StockQuote $stockQuote */
        foreach ($stockQuotes->toArray() as $stockQuote) {
            $existStockQuote = $event->getEntityManager()
                ->getRepository('YahooFinanceBundle:StockQuote')
                ->findOneBy(['symbol' => $stockQuote->getSymbol()]);

            if (!$existStockQuote) {
                $existStockQuote = new StockQuote($stockQuote->getSymbol());
                $event->getEntityManager()->persist($existStockQuote);
                $event->getEntityManager()->flush();
            }
            $stockQuotes->removeElement($stockQuote);
            $stockQuotes->add($existStockQuote);
        }
    }
}
