<?php

// src/EventListener/RedirectAfterLoginListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class RedirectAfterLoginListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $request = $event->getRequest();
        $referer = $request->headers->get('referer');
        $response = new RedirectResponse($referer ?: $this->urlGenerator->generate('security/register.html.twig'));
        $event->setResponse($response);
    }
}