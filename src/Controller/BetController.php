<?php

namespace App\Controller;

use App\Service\BetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends AbstractController
{
    private $betService;

    public function __construct(BetService $betService)
    {
        $this->betService = $betService;
    }

    /**
     * @Route("/bet/generate", name="bet_generate")
     */
    public function betGenerate(): Response
    {
        $this->betService->generateBets();

        return $this->json('ok');
    }
}
