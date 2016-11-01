<?php

namespace YahooFinanceBundle\Subscribers;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class RegistrationConfirmationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationConfirm'];
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationConfirm(FilterUserResponseEvent $event)
    {
        /** @var RedirectResponse $response */
        $response = $event->getResponse();
        $url = $this->router->generate('yahoo_finance_index');

        $response->setTargetUrl($url);
    }
}
