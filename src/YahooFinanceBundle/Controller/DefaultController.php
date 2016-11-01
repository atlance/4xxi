<?php

namespace YahooFinanceBundle\Controller;

use YahooFinanceBundle\Form\PortfolioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="yahoo_finance_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (!$this->getUser()) {
            return $this->render('@YahooFinance/index.html.twig', [
                'registrationForm' => $this->get('fos_user.registration.form.factory')->createForm()->createView(),
            ]);
        }

        return $this->render('@YahooFinance/index.html.twig', [
            'portfolioForm' => $this->createForm(PortfolioType::class)->createView(),
        ]);
    }
}
