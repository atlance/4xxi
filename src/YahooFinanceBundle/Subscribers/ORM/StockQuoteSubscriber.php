<?php

namespace YahooFinanceBundle\Subscribers\ORM;

use Doctrine\ORM\Event\PreFlushEventArgs;
use YahooFinanceApiBundle\Services\YahooFinanceAPI;
use YahooFinanceBundle\Entity\Company;
use YahooFinanceBundle\Entity\StockExchange;
use YahooFinanceBundle\Entity\StockQuote;

class StockQuoteSubscriber
{
    /**
     * @var YahooFinanceAPI
     */
    private $api;

    public function __construct(YahooFinanceAPI $api)
    {
        $this->api = $api->replaceAliases([
            'n' => 'companyName',
            'x' => 'stockExchangeCode',
        ]);
    }

    public function preFlush(StockQuote $stockQuote, PreFlushEventArgs $event)
    {
        if (!$stockQuote->getId()) {
            $companyRepository = $event->getEntityManager()
                ->getRepository('YahooFinanceBundle:Company');
            $stockExchangeRepository = $event->getEntityManager()
                ->getRepository('YahooFinanceBundle:StockExchange');

            $data = $this->api->fetchQuotes([$stockQuote->getSymbol()], ['companyName', 'stockExchangeCode']);
            $company = $companyRepository->findOneBy(['name' => $data[0]['companyName']]);
            $stockExchange = $stockExchangeRepository->findOneBy(['code' => $data[0]['stockExchangeCode']]);

            $stockQuote->setCompany($company ?? new Company($data[0]['companyName']));
            $stockQuote->setStockExchange($stockExchange ?? new StockExchange($data[0]['stockExchangeCode']));
        }
    }
}
