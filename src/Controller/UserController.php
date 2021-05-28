<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\BetService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="users_list")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/users_list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/{username}", name="user_info")
     * @param User $user
     * @param BetService $betService
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function user_info(User $user, BetService $betService): Response
    {
        return $this->render('user/user_info.html.twig', [
            'user' => $user,
            'bets' => $betService->calcUserBets($user),
        ]);
    }
}
