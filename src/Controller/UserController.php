<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function user_info(User $user): Response
    {
        return $this->render('user/user_info.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/update_email/{username}", name="user_update_email")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param User $user
     * @return JsonResponse
     */
    public function updateUserEmail(Request $request, EntityManagerInterface $em, User $user)
    {
        $user->setLastEmail($user->getEmail());
        $user->setEmail($request->request->get('email'));
        $em->flush();

        return $this->json(['success' => 'ok']);
    }
}
