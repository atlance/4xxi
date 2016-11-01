<?php

namespace YahooFinanceBundle\Controller;

use YahooFinanceBundle\Entity\Portfolio;
use YahooFinanceBundle\Form\PortfolioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/yahoo-finance/portfolio", condition="request.isXmlHttpRequest()")
 */
class PortfolioController extends Controller
{
    /**
     * @Route("", name="yahoo_finance_portfolio_new")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        $portfolio = $this->get('serializer')->deserialize($request->getContent(), Portfolio::class, 'json');
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $portfolio = $form->getData();
            $em->persist($portfolio);
            $em->flush();

            return new JsonResponse($this->get('serializer')->serialize($portfolio, 'json'), 201);
        }

        return new JsonResponse(null, 400);
    }

    /**
     * @Route("/{id}", name="yahoo_finance_portfolio_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $portfoliosRepository = $em->getRepository('YahooFinanceBundle:Portfolio');

        $portfolio = $portfoliosRepository->findOneBy(['id' => $request->get('id'), 'user' => $this->getUser()]);

        if ($portfolio) {
            $portfolio->getStockQuotes()->clear();

            $em->remove($portfolio);
            $em->flush();

            return new JsonResponse();
        }

        return new JsonResponse(null, 404);
    }

    /**
     * @Route("/{id}/{stockId}", name="yahoo_finance_portfolio_stock_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteStockAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $portfoliosRepository = $em->getRepository('YahooFinanceBundle:Portfolio');
        $stockQuoteRepository = $em->getRepository('YahooFinanceBundle:StockQuote');

        $portfolio = $portfoliosRepository->findOneBy(['id' => $request->get('id'), 'user' => $this->getUser()]);
        $stock = $stockQuoteRepository->findOneBy(['id' => $request->get('stockId')]);

        if ($portfolio && $stock) {
            if ($portfolio->getStockQuotes()->contains($stock)) {
                $portfolio->getStockQuotes()->removeElement($stock);

                $em->persist($portfolio);
                $em->flush();
            }

            return new JsonResponse();
        }

        return new JsonResponse(null, 404);
    }
}
