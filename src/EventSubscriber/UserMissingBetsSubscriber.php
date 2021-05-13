<?php

namespace App\EventSubscriber;

use App\Service\BetService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class UserMissingBetsSubscriber implements EventSubscriberInterface
{
    private $session;
    private $betService;

    public function __construct(SessionInterface $session, BetService $betService)
    {
        $this->session    = $session;
        $this->betService = $betService;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $this->session->set('userMissingBets', $this->betService->countUserMissingBets());
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
